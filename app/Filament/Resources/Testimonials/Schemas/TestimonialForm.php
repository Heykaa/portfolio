<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
                ->hidden(fn ($livewire) => $livewire instanceof \Filament\Resources\RelationManagers\RelationManager),

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
                ->disk('public')
                ->directory('testimonials')
                ->visibility('public')
                ->preserveFilenames()
                ->avatar()
                ->imageEditor()
                ->downloadable()
                ->openable()
                ->maxSize(2048),

            TextInput::make('sort_order')
                ->required()
                ->numeric()
                ->default(0),

            Toggle::make('enabled')
                ->default(true)
                ->inline(false),
        ]);
    }
}
