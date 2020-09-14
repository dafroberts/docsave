<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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
        'document_url',
        'document_file_class',
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

    public function getDocumentFileClassAttribute() {
        try {
            $doc = new File(Storage::disk('documents')->path('sorted/'.$this->location));
            return [
                'extension' => $doc->extension(),
            ];
        } catch(Exception $e) {
            return null;
        }
    }
}
