<?php

namespace App\Services\SpatieMediaLibrary;

use App\Models\Announcement;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        return implode('/', array_filter([
            $this->getCollectionDirectory($media),
            $this->getFileDirectory($media),
        ]));
    }

    protected function getEnvironmentDirectory(Media $media): string
    {
        return config('app.env');
    }

    protected function getCollectionDirectory(Media $media): string
    {
        return $media->collection_name;
    }

    protected function getModelDirectory(Media $media): ?string
    {
        return $media->model?->getKey();
    }

    protected function getFileDirectory(Media $media): string
    {
        return md5($media->getKey().$media->name);
    }
}
