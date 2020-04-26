<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Document extends Model
{
    use Searchable;

    protected $fillable = ['filename', 'content', 'path', 'status'];

    public function markAsPublished() {
        $this->status = 'published';
    }

    public function markAsCompleted() {
        $this->status = 'completed';
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
