<?php

namespace App\Models;

use App\Http\Livewire\Documents\DocumentHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;

class Document extends Model
{
    use HasTags, HasFactory, Searchable;

    protected $fillable = ['filename', 'content', 'path', 'status'];
    protected $appends = ['file_size', 'file_type', 'simpleTags'];
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Get the dates (mentioned in its contents) of the document
     */
    public function dates()
    {
        return $this->hasMany(DocumentDate::class);
    }

    /**
     * simplified tags accessor - see https://laravel.com/docs/8.x/eloquent-mutators#defining-an-accessor
     * 
     */
    public function getSimpleTagsAttribute()
    {
        return $this->tags()
                ->get()
                ->transform(function ($item, $key) {
                        return $item['name'];
                    })
                ->all();
    }

    /**
     * file size accessor - see https://laravel.com/docs/8.x/eloquent-mutators#defining-an-accessor
     * 
     * FIXME: move this out of the eloquent model 
     */
    public function getFileSizeAttribute() {
        if (!Storage::disk('documents')->exists($this->path)) {
            return "unknown";
        }
        $size = Storage::disk('documents')->size($this->path);

        if ($size > 1000000) {
            $result = round(($size / 1024 / 1024), 1) . " MB";
        } else if ($size > 1000) {
            $result = round($size / 1024) . " KB";
        } else {
            $result = $size . " B";
        }

        return $result;
    }

    /**
     * mime type accessor - see https://laravel.com/docs/8.x/eloquent-mutators#defining-an-accessor
     * 
     * FIXME: make this more dynamic and move this out of the eloquent model 
     */
    public function getFileTypeAttribute() {
        return "PDF";
    }

    public function markAsPublished() {
        $this->status = DocumentStatus::PUBLISHED;
    }

    public function markAsIncomplete() {
        $this->status = DocumentStatus::INCOMPLETE;
    }

    public function saveAndUpdateStatus(array $options = []) {
        if ( empty ($this->content) || empty ($this->path)) { 
            $this->markAsIncomplete();
        } else { 
            $this->markAsPublished();
        }
        
        $this->save($options);
    }

     /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'documents_index';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {        
        $array = collect($this->toArray())->only([
                    'filename',
                    'content',
                  ])->toArray();
        $array[$this->getKeyName()] = $this->getKey();

        /* TODO: remove useless words (prepositions etc.) */

        return $array;
    }
}
