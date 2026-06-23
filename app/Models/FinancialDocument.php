<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialDocument extends Model
{
    use HasFactory, \App\Traits\HasTranslations;

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'year',
        'type',
        'media_id',
        'is_published',
        'published_at',
        'sort_order',
    ];

    protected $casts = [
        'year' => 'integer',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'sort_order' => 'integer',
        'type' => \App\Enums\DocumentType::class,
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
