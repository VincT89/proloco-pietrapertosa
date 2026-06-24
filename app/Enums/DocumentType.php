<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocumentType: string implements HasLabel
{
    case BALANCE_SHEET = 'balance_sheet';
    case BUDGET = 'budget';
    case FINANCIAL_REPORT = 'financial_report';
    case APPROVAL_MINUTES = 'approval_minutes';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match($this) {
            self::BALANCE_SHEET => 'Bilancio consuntivo',
            self::BUDGET => 'Bilancio preventivo',
            self::FINANCIAL_REPORT => 'Rendiconto',
            self::APPROVAL_MINUTES => 'Verbale approvazione bilancio',
            self::OTHER => 'Altro documento contabile',
        };
    }
}
