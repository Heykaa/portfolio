<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Brand')
                ->schema([
                    TextInput::make('brand_name')->maxLength(255),
                    FileUpload::make('favicon_path')
                        ->disk('public')
                        ->directory('site')
                        ->image(),
                ])
                ->columns(2),

            FormSection::make('Hero')
                ->schema([
                    TextInput::make('hero_title')->maxLength(255),
                    Textarea::make('hero_subtitle')->rows(3),
                    TextInput::make('hero_cta_text')->maxLength(255),
                    TextInput::make('hero_cta_url')->maxLength(255),
                    FileUpload::make('hero_image_path')
                        ->disk('public')
                        ->directory('site/hero')
                        ->image(),
                    FileUpload::make('hero_video_path')
                        ->disk('public')
                        ->directory('site/hero')
                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime']),
                ])
                ->columns(2),

            FormSection::make('Social Links')
                ->schema([
                    Repeater::make('social_links')
                        ->schema([
                            TextInput::make('label')->required(),
                            TextInput::make('url')->required(),
                        ])
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand_name')->searchable(),
                TextColumn::make('hero_title')->searchable(),
                TextColumn::make('updated_at')->since(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
