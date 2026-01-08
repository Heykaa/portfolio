<?php

namespace App\Filament\Resources\Sections\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

use Filament\Forms\Components\KeyValue;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FormSection::make('Basic Configuration')
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash', 'lowercase'])
                            ->disabledOn('edit'),
                        TextInput::make('title')
                            ->required(),
                        TextInput::make('subtitle')
                            ->default(null),
                        Toggle::make('enabled')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),

                FormSection::make('Hero Settings')
                    ->hidden(fn($record) => $record?->key !== 'hero')
                    ->statePath('data')
                    ->schema([
                        TextInput::make('video_url')
                            ->label('Hero Video URL')
                            ->url(),
                    ]),

                FormSection::make('Marquee Settings')
                    ->hidden(fn($record) => $record?->key !== 'marquee')
                    ->statePath('data')
                    ->schema([
                        TextInput::make('text')
                            ->label('Marquee Text')
                            ->required(),
                    ]),

                FormSection::make('Stack3D Settings')
                    ->hidden(fn($record) => $record?->key !== 'stack3d')
                    ->statePath('data')
                    ->schema([
                        TagsInput::make('skills')
                            ->label('Tech Stack Skills'),
                    ]),

                FormSection::make('Contact Settings')
                    ->hidden(fn($record) => $record?->key !== 'contact')
                    ->statePath('data')
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required(),
                        Textarea::make('description')
                            ->rows(3),
                    ]),

                FormSection::make('Layout Settings')
                    ->description('Configuration for section appearance')
                    ->statePath('layout')
                    ->schema([
                        TextInput::make('columns')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->default(1),
                        TextInput::make('gap')
                            ->default('gap-8'),
                        TextInput::make('padding')
                            ->default('py-20'),
                        Toggle::make('full_width')
                            ->default(false),
                    ]),

                FormSection::make('Advanced Data (JSON)')
                    ->description('Direct access to layout and data fields')
                    ->collapsed()
                    ->schema([
                        KeyValue::make('layout')
                            ->keyLabel('Option')
                            ->valueLabel('Value')
                            ->default(null),
                        KeyValue::make('data')
                            ->keyLabel('Key')
                            ->valueLabel('Content')
                            ->default(null),
                    ]),
            ]);
    }
}
