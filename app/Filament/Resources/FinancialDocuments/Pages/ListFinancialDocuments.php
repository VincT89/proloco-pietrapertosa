<?php

namespace App\Filament\Resources\FinancialDocuments\Pages;

use App\Filament\Resources\FinancialDocuments\FinancialDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinancialDocuments extends ListRecords
{
    protected static string $resource = FinancialDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
