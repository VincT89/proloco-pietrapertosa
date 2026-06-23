<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MediaManager
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    /**
     * Upload to Cloudinary and create DB record.
     * Reverts Cloudinary upload if DB fails.
     */
    public function upload(UploadedFile $file, string $folder = 'proloco_pietrapertosana'): Media
    {
        $upload = null;
        try {
            // 1. Upload to Cloudinary first
            $upload = $this->cloudinary->uploadMedia($file, $folder);
            
            // 2. DB Transaction
            return DB::transaction(function () use ($upload, $file) {
                $isVideo = ($upload['resource_type'] ?? null) === 'video';
                
                return Media::create([
                    'type' => $isVideo ? 'video' : (($upload['resource_type'] ?? '') === 'raw' ? 'document' : 'image'),
                    'provider' => 'cloudinary',
                    'resource_type' => $upload['resource_type'] ?? 'image',
                    'url' => $upload['secure_url'] ?? $upload['url'],
                    'public_id' => $upload['public_id'],
                    'alt' => $file->getClientOriginalName(),
                    'thumbnail_url' => $isVideo 
                        ? str_replace('/video/upload/', '/video/upload/so_1,w_600,h_400,c_fill,f_jpg/', $upload['secure_url'] ?? $upload['url']) 
                        : null,
                    'metadata' => [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'format' => $upload['format'] ?? $file->getClientOriginalExtension(),
                    ],
                ]);
            });
        } catch (\Exception $e) {
            // 3. Compensation
            if ($upload && isset($upload['public_id'])) {
                try {
                    $this->cloudinary->deleteMedia($upload['public_id'], $upload['resource_type'] ?? 'image');
                } catch (\Exception $deleteEx) {
                    Log::error('Failed to compensate Cloudinary upload', [
                        'public_id' => $upload['public_id'],
                        'error' => $deleteEx->getMessage()
                    ]);
                }
            }
            Log::error('Media upload failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Delete Media record and remove from Cloudinary
     */
    public function delete(Media $media): bool
    {
        if ($media->isInUse()) {
            return false;
        }

        try {
            DB::transaction(function () use ($media) {
                // Delete relations first (though Laravel might handle this if cascade is set, but better safe)
                DB::table('mediables')->where('media_id', $media->id)->delete();
                // If it's a cover somewhere, we might need to nullify it. We assume foreign keys restrict or cascade properly.
                // Usually cover_media_id is set to null on delete (set null).
                $media->delete();
            });

            // If DB deletion succeeds, remove from Cloudinary
            if ($media->public_id) {
                $this->cloudinary->deleteMedia($media->public_id, $media->resource_type ?? 'image');
            } elseif ($media->url && $media->provider === 'cloudinary') {
                $this->cloudinary->deleteMediaByUrl($media->url, $media->resource_type ?? 'image');
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Media deletion failed', ['media_id' => $media->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
