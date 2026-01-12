<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('section_id')
                ->relationship('section', 'title')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->title ?? $record->key)
                ->required()
                ->searchable()
                ->preload()
                ->hidden(fn ($livewire) =>
                    $livewire instanceof \Filament\Resources\RelationManagers\RelationManager
                ),

            Textarea::make('quote')
                ->required()
                ->columnSpanFull(),

            TextInput::make('name')
                ->required(),

            TextInput::make('role')
                ->default(null),

FileUpload::make('avatar_path')
    ->label('Avatar')
    ->image()
    ->disk('local')
    ->directory('uploads/testimonials')
    ->visibility('private')
    ->avatar()
    ->imageEditor()
    ->maxSize(2048)
    ->required();


            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->required(),

            Toggle::make('enabled')
                ->default(true)
                ->inline(false),
        ]);
    }
}
