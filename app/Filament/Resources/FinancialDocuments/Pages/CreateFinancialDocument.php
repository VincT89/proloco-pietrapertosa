<?php

namespace App\Filament\Resources\FinancialDocuments\Pages;

use App\Filament\Resources\FinancialDocuments\FinancialDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialDocument extends CreateRecord
{
    protected static string $resource = FinancialDocumentResource::class;
}
