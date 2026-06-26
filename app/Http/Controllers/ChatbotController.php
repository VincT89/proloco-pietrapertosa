<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Chatbot\ChatbotService;

class ChatbotController extends Controller
{
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    public function message(Request $request, string $locale)
    {
        if (!in_array($locale, ['it', 'en'])) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:2|max:500',
            'context' => 'nullable|array',
            'context.topic' => 'nullable|string|max:100',
            'context.entity_type' => 'nullable|string|max:50',
            'context.entity_id' => 'nullable|integer',
        ]);

        $response = $this->chatbotService->handleMessage($validated['message'], $locale, $validated['context'] ?? []);

        return response()->json($response);
    }

    public function suggestions(Request $request, string $locale)
    {
        if (!in_array($locale, ['it', 'en'])) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        $suggestions = $this->chatbotService->getInitialSuggestions($locale);

        return response()->json($suggestions);
    }
}
