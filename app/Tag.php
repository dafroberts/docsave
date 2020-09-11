<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Get all of the documents that are assigned this tag.
     */
    public function documents()
    {
        return $this->morphedByMany(Document::class, 'taggable');
    }
}
