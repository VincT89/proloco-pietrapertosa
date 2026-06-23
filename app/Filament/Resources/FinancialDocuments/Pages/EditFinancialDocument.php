<?php

namespace App\Filament\Resources\FinancialDocuments\Pages;

use App\Filament\Resources\FinancialDocuments\FinancialDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFinancialDocument extends EditRecord
{
    protected static string $resource = FinancialDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
