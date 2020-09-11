<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name',
        'location',
        'created_at',
    ];

    protected $with = [
        'tags',
    ];

    protected $casts = [
        'created_at'  => 'date:jS M Y',
    ];

    protected $appends = [
        'document_url'
    ];

    /**
     * Get all of the tags for the document.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function getDocumentUrlAttribute()
    {
        return asset('storage/documents/sorted/'.$this->location);
    }
}
