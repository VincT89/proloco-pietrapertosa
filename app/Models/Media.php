<?php

namespace App\Models;

use App\Services\MediaUsage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Media extends Model
{
    protected $fillable = [
        'type', 'provider', 'public_id', 'url', 'source_url', 'embed_url',
        'thumbnail_url', 'duration', 'metadata', 'resource_type', 'alt',
        'caption', 'folder', 'original_name', 'content_hash',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public function optimizedUrl(string $preset = 'default'): ?string
    {
        return cloudinary_url($this->url, $preset);
    }

    public function isVideo(): bool
    {
        return $this->type === 'video' || $this->resource_type === 'video';
    }

    public function optimizedVideoUrl(): ?string
    {
        if (! $this->url || ! str_contains($this->url, '/video/upload/')) {
            return $this->url;
        }

        return str_replace('/video/upload/', '/video/upload/q_auto,f_auto/', $this->url);
    }

    public function videoThumbnailUrl(string $preset = 'card'): ?string
    {
        if (! $this->url || ! str_contains($this->url, '/video/upload/')) {
            return null;
        }

        $transform = match ($preset) {
            'thumb' => 'so_1,w_400,h_300,c_fill,f_jpg',
            'card' => 'so_1,w_600,h_400,c_fill,f_jpg',
            default => 'so_1,w_600,h_400,c_fill,f_jpg',
        };

        return str_replace('/video/upload/', '/video/upload/'.$transform.'/', $this->url);
    }

    public function isInUse(): bool
    {
        return DB::table('mediables')->where('media_id', $this->id)->exists()
            || count(app(MediaUsage::class)->forMedia(collect([$this]), fresh: true)[$this->id] ?? []) > 0;
    }

    public function displayName(): string
    {
        return $this->original_name ?: ($this->metadata['original_name'] ?? null)
            ?: $this->caption ?: $this->alt ?: basename(parse_url($this->url ?? '', PHP_URL_PATH) ?: '') ?: 'File senza nome';
    }

    public function previewUrl(): ?string
    {
        return $this->type === 'image' ? $this->optimizedUrl('small')
            : ($this->thumbnail_url ?: ($this->isVideo() ? $this->videoThumbnailUrl() : null));
    }

    public function scopeSearchLibrary(Builder $query, string $search): void
    {
        if (trim($search) === '') {
            return;
        }

        $query->where(function ($query) use ($search) {
            foreach (['original_name', 'metadata->original_name', 'alt', 'caption', 'public_id'] as $column) {
                $query->orWhere($column, 'like', '%'.trim($search).'%');
            }
        });
    }

    public function scopeAcceptedDocuments(Builder $query, array $mimeTypes): void
    {
        $formats = array_keys(array_filter([
            'pdf' => in_array('application/pdf', $mimeTypes, true),
            'doc' => in_array('application/msword', $mimeTypes, true),
            'docx' => in_array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', $mimeTypes, true),
            'zip' => in_array('application/zip', $mimeTypes, true) || in_array('application/x-zip-compressed', $mimeTypes, true),
        ]));

        $query->where(fn ($query) => $query->where('type', '!=', 'document')
            ->orWhere(fn ($query) => $query->whereIn('metadata->mime_type', $mimeTypes)
                ->orWhere(fn ($query) => $query->whereNull('metadata->mime_type')->whereIn('metadata->format', $formats))));
    }
}
