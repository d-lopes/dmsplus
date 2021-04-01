<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;
use Mimey\MimeTypes;
use Spatie\Tags\HasTags;

use function PHPUnit\Framework\isEmpty;

/**
 * Eloquent Model for Documents that acts as a data transfer object (DTO) and entity from the database with barely any logic.
 */
class Document extends Model
{
    use HasTags, HasFactory, Searchable;

    // define date which can be directly manipulated
    protected $fillable = ['filename', 'size', 'mime_type','content', 'path', 'status'];

    // define virtual data fields that shall be included in array and JSON representations
    protected $appends = ['file_size', 'file_type', 'simple_tags', 'has_duplicates', 'is_original'];
    
    // define date columns
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the dates (mentioned in its contents) of the document
     */
    public function dates()
    {
        return $this->hasMany(DocumentDate::class);
    }

    public function markAsPublished() {
        $this->status = DocumentStatus::PUBLISHED;
    }

    public function markAsIncomplete() {
        $this->status = DocumentStatus::INCOMPLETE;
    }

    
    /*****************************************************************************************************************
     * influence search behaviour of Laravel Scout (see https://laravel.com/docs/5.4/scout#searching)                *
     *****************************************************************************************************************/
    
    /**
     * define index name for the model.
     */
    public function searchableAs() {
        return 'documents_index';
    }

    /**
     * define indexable data array for the model.
     */
    public function toSearchableArray() {        
        $array = collect($this->toArray())->only([
                    'filename',
                    'content',
                  ])->toArray();
        $array[$this->getKeyName()] = $this->getKey();

        /* TODO: remove useless words (prepositions etc.) */

        return $array;
    }

    /*****************************************************************************************************************
     * accessor methods for virtual fields (see https://laravel.com/docs/8.x/eloquent-mutators#defining-an-accessor) *
     *****************************************************************************************************************/
    
    public function getSimpleTagsAttribute()
    {
        return $this->tags()
                ->get()
                ->transform(function ($item, $key) {
                        return $item['name'];
                    })
                ->all();
    }

    public function getFileSizeAttribute() {
        $result = "unknown";
        if ($this->size > 1000000) {
            $result = round(($this->size / 1024 / 1024), 1) . " MB";
        } else if ($this->size > 1000) {
            $result = round($this->size / 1024) . " KB";
        } else if ($this->size > 0) {
            $result = $this->size . " B";
        }

        return $result;
    }

    public function getFileTypeAttribute() {
        $mimes = new MimeTypes();
        $ext = $mimes->getExtension($this->mime_type);

        return strtoupper($ext);
    }

    public function getDuplicatesAttribute() {
        // Guard: exclude documents with PENDING status
        if ($this->status == DocumentStatus::PENDING) {
            return; 
        }

        // collect matching documents (except for the current document)
        $result = Document::where([
                ['id', '<>', $this->id],
                ['status', '<>', DocumentStatus::PENDING],
                ['md5_hash', '=', $this->md5_hash],
            ])
            ->orWhere([
                ['id', '<>', $this->id],
                ['status', '<>', DocumentStatus::PENDING],
                ['size', '=', $this->size],
                ['mime_type', '=', $this->mime_type],
            ])
            ->orWhere([
                ['id', '<>', $this->id],
                ['status', '<>', DocumentStatus::PENDING],
                ['filename', '=', $this->filename],
            ])
            ->orderBy('created_at', 'asc') // order by creation date in ascending order => oldest document is on top
            ->get();

        return $result;
    }

    public function getHasDuplicatesAttribute() {
        $duplicates = $this->getDuplicatesAttribute();
        return (!empty($duplicates) && $duplicates->count() > 0);
    }
        
    public function getIsOriginalAttribute() {
        // Guard: if there are no duplicates then this is automatically an original
        if (!$this->getHasDuplicatesAttribute()) {
            Log::info('no duplicates!');
            return true; 
        }

        // check duplicates
        $oldest = $this->duplicates->first();
        return $oldest->created_at < $this->created_at;
    }

}
