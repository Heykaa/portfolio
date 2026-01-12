<?php

namespace App\Filament\Resources\CaseStudies\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Schema;

class CaseStudyForm
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

            TextInput::make('title')
                ->required(),

            Textarea::make('caption')
                ->default(null)
                ->columnSpanFull(),

            TagsInput::make('tags')
                ->default(null)
                ->columnSpanFull(),

            FileUpload::make('image_path')
                ->label('Case Study Image')
                ->image()
                ->disk('public')
                ->directory('case-studies')
                ->visibility('public')
                ->imageEditor()
                ->maxSize(4096)
                ->required(),

            TextInput::make('url')
                ->default(null),

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
