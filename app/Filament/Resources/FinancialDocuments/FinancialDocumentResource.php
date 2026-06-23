<?php

namespace App\Filament\Resources\FinancialDocuments;

use App\Filament\Resources\FinancialDocuments\Pages\CreateFinancialDocument;
use App\Filament\Resources\FinancialDocuments\Pages\EditFinancialDocument;
use App\Filament\Resources\FinancialDocuments\Pages\ListFinancialDocuments;
use App\Filament\Resources\FinancialDocuments\Schemas\FinancialDocumentForm;
use App\Filament\Resources\FinancialDocuments\Tables\FinancialDocumentsTable;
use App\Models\FinancialDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FinancialDocumentResource extends Resource
{
    protected static ?string $model = FinancialDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    
    protected static ?string $modelLabel = 'Documento';
    
    protected static ?string $pluralModelLabel = 'Bilanci e Documenti';

    public static function getNavigationGroup(): ?string
    {
        return 'Pro Loco';
    }

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return FinancialDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinancialDocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinancialDocuments::route('/'),
            'create' => CreateFinancialDocument::route('/create'),
            'edit' => EditFinancialDocument::route('/{record}/edit'),
        ];
    }
}
