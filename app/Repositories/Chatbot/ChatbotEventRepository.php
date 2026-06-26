<?php

namespace App\Repositories\Chatbot;

use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ChatbotEventRepository
{
    public function getNextEvent(): ?Event
    {
        return Event::where('status', 'published')
            ->where('start_date', '>=', now()->startOfDay())
            ->orderBy('start_date', 'asc')
            ->first();
    }

    public function getWeekendEvents(): Collection
    {
        $saturday = Carbon::now()->next(Carbon::SATURDAY)->startOfDay();
        $sunday = $saturday->copy()->addDay()->endOfDay();

        return Event::where('status', 'published')
            ->where(function($query) use ($saturday, $sunday) {
                $query->whereBetween('start_date', [$saturday, $sunday])
                      ->orWhereBetween('end_date', [$saturday, $sunday]);
            })
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function getTodayEvents(): Collection
    {
        return Event::where('status', 'published')
            ->whereDate('start_date', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function getAllFutureEvents(): Collection
    {
        return Event::where('status', 'published')
            ->where('start_date', '>=', now()->startOfDay())
            ->orderBy('start_date', 'asc')
            ->take(5) // limit to 5
            ->get();
    }
}
