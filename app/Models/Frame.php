<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;

class Frame extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/png';
            });

        $this
            ->addMediaCollection('thumb')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg';
            });
    }

    public function photoPapers()
    {
        return $this->hasMany(PhotoPaper::class);
    }
}
