<?php

namespace App\Filament\Resources\Sections\RelationManagers;

use App\Filament\Resources\Testimonials\Schemas\TestimonialForm;
use App\Filament\Resources\Testimonials\Tables\TestimonialsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TestimonialsRelationManager extends RelationManager
{
    protected static string $relationship = 'testimonials';

    public function form(Schema $schema): Schema
    {
        return TestimonialForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return TestimonialsTable::configure($table)
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
