<?php

namespace App\Filament\Resources\Sections\RelationManagers;

use App\Filament\Resources\CaseStudies\Schemas\CaseStudyForm;
use App\Filament\Resources\CaseStudies\Tables\CaseStudiesTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class CaseStudiesRelationManager extends RelationManager
{
    protected static string $relationship = 'caseStudies';

    public function form(Schema $schema): Schema
    {
        return CaseStudyForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return CaseStudiesTable::configure($table)
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
