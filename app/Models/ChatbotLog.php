<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotLog extends Model
{
    protected $fillable = [
        'locale',
        'query',
        'mode',
        'matched_destination',
        'result_count',
    ];
}
