<?php

namespace App\Filament\Resources\Sections\RelationManagers;

use App\Filament\Resources\Stats\Schemas\StatForm;
use App\Filament\Resources\Stats\Tables\StatsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class StatsRelationManager extends RelationManager
{
    protected static string $relationship = 'stats';

    public function form(Schema $schema): Schema
    {
        return StatForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return StatsTable::configure($table)
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
