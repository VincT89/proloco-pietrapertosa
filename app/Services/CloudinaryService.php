<?php

namespace App\Services;

use App\Exceptions\MediaUploadException;
use App\Models\Media;
use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    /**
     * Upload an image or video to Cloudinary and return details
     */
    public function uploadMedia(UploadedFile $file, string $folder = 'proloco_pietrapertosana')
    {
        $mime = $file->getMimeType() ?? '';

        $isVideo = str_starts_with($mime, 'video/');

        $maxBytes = $isVideo
            ? 100 * 1024 * 1024
            : 10 * 1024 * 1024;

        if ($file->getSize() > $maxBytes) {
            $maxMb = $isVideo ? 100 : 10;

            throw new MediaUploadException(
                'Il file "'.$file->getClientOriginalName().'" supera il limite massimo di '.$maxMb.' MB.'
            );
        }
        $isDocument = preg_match(
            '/application\/(pdf|msword|vnd\.openxmlformats-officedocument|zip|x-zip-compressed|rar)/i',
            $mime
        );

        $response = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
            'resource_type' => $isDocument ? 'raw' : 'auto',
            'type' => 'upload',
            'access_mode' => 'public',
        ]);

        return [
            'public_id' => $response['public_id'],
            'url' => $response['secure_url'],
            'secure_url' => $response['secure_url'],
            'resource_type' => $response['resource_type'] ?? 'image',
            'duration' => $response['duration'] ?? null,
            'format' => $response['format'] ?? null,
        ];
    }

    /**
     * Delete a media from Cloudinary by its public ID
     */
    public function deleteMedia(string $publicId, string $resourceType = 'image')
    {
        return $this->cloudinary->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);
    }

    /**
     * Delete a media from Cloudinary by its secure URL
     */
    public function deleteMediaByUrl(string $url, string $resourceType = 'image')
    {
        // Example: https://res.cloudinary.com/dn8nhmtch/image/upload/v1780898352/proloco_pietrapertosana/hisednzzwaiholpvdikm.jpg
        // Extracts: proloco_pietrapertosana/hisednzzwaiholpvdikm
        if (preg_match('/upload\/(?:v\d+\/)?([^\.]+)/', $url, $matches)) {
            $publicId = $matches[1];

            return $this->deleteMedia($publicId, $resourceType);
        }

        return false;
    }

    /**
     * Delete a temporary upload from Cloudinary
     */
    public function cleanupTemporaryUpload(string $url)
    {
        $cachedId = Cache::get('last_upload_'.$url);
        if ($cachedId) {
            $media = Media::find($cachedId);
            if ($media) {
                $this->deleteMediaByUrl($media->url, $media->resource_type ?? 'image');
                $media->delete();
            } else {
                $this->deleteMediaByUrl($url);
            }
            Cache::forget('last_upload_'.$url);

            return true;
        }

        return false;
    }
}
