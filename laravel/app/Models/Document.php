<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;

class Document extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['filename', 'content', 'path', 'status'];

    public function markAsPublished() {
        $this->status = 'published';
    }

    public function markAsIncomplete() {
        $this->status = 'incomplete';
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
