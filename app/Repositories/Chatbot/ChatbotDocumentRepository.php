<?php

namespace App\Repositories\Chatbot;

use App\Models\FinancialDocument;
use App\Models\News;
use Illuminate\Support\Collection;

class ChatbotDocumentRepository
{
    public function getLatestFinancialDocuments(): Collection
    {
        return FinancialDocument::with('media')
            ->where('is_published', true)
            ->whereNotNull('media_id')
            ->orderBy('year', 'desc')
            ->take(3)
            ->get();
    }

    public function getNewsWithAttachments(): Collection
    {
        // Return news that have attachments
        return News::with('attachmentsMedia')
            ->where('status', 'published')
            ->whereHas('attachmentsMedia')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
    }
}
