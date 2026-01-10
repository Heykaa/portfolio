<?php

namespace App\Filament\Resources\SiteSettings;

use App\Models\SiteSetting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components;
use Filament\Tables\Table;
use App\Filament\Resources\SiteSettings\SiteSettingResource\Pages\ManageSiteSettings;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string|\UnitEnum|null $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Site Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Components\Section::make('Brand')
                ->schema([
                    Components\TextInput::make('brand_name')
                        ->maxLength(255),

                    Components\FileUpload::make('favicon_path')
                        ->disk('public')
                        ->directory('site')
                        ->image(),
                ])
                ->columns(2),

            Components\Section::make('Hero')
                ->schema([
                    Components\TextInput::make('hero_title')
                        ->maxLength(255),

                    Components\Textarea::make('hero_subtitle')
                        ->rows(3),

                    Components\TextInput::make('hero_cta_text')
                        ->maxLength(255),

                    Components\TextInput::make('hero_cta_url')
                        ->maxLength(255),

                    Components\FileUpload::make('hero_image_path')
                        ->disk('public')
                        ->directory('site/hero')
                        ->image(),

                    Components\FileUpload::make('hero_video_path')
                        ->disk('public')
                        ->directory('site/hero')
                        ->acceptedFileTypes([
                            'video/mp4',
                            'video/webm',
                            'video/quicktime',
                        ]),
                ])
                ->columns(2),

            Components\Section::make('Social Links')
                ->schema([
                    Components\Repeater::make('social_links')
                        ->schema([
                            Components\TextInput::make('label')->required(),
                            Components\TextInput::make('url')->required(),
                        ])
                        ->default([])
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
