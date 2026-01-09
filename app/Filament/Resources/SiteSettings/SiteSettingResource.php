<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    /**
     * IMPORTANT:
     * Types MUST match Filament v4 exactly (PHP 8.4 strict)
     */
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string|UnitEnum|null   $navigationLabel = 'Site Settings';
    protected static string|UnitEnum|null   $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Brand')
                    ->schema([
                        Forms\Components\TextInput::make('brand_name')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('favicon_path')
                            ->disk('public')
                            ->directory('site')
                            ->image(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Hero')
                    ->schema([
                        Forms\Components\TextInput::make('hero_title')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('hero_subtitle')
                            ->rows(3),

                        Forms\Components\TextInput::make('hero_cta_text')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('hero_cta_url')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('hero_image_path')
                            ->disk('public')
                            ->directory('site/hero')
                            ->image(),

                        Forms\Components\FileUpload::make('hero_video_path')
                            ->disk('public')
                            ->directory('site/hero')
                            ->acceptedFileTypes([
                                'video/mp4',
                                'video/webm',
                                'video/quicktime',
                            ]),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Social Links')
                    ->schema([
                        Forms\Components\Repeater::make('social_links')
                            ->schema([
                                Forms\Components\TextInput::make('label')->required(),
                                Forms\Components\TextInput::make('url')->required(),
                            ])
                            ->default([])
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand_name')->searchable(),
                Tables\Columns\TextColumn::make('hero_title')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'edit'  => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
