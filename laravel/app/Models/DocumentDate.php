<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentDate extends Model
{
    use HasFactory;

    protected $fillable = ['date_value'];
    
    /**
     * Get the post that owns the comment.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
