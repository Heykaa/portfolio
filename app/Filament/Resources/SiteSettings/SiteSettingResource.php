<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section as FormSection;

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
                    Forms\Components\TextInput::make('brand_name')->maxLength(255),
                    Forms\Components\FileUpload::make('favicon_path')
                        ->disk('public')
                        ->directory('site')
                        ->image(),
                ])
                ->columns(2),

            FormSection::make('Hero')
                ->schema([
                    Forms\Components\TextInput::make('hero_title')->maxLength(255),
                    Forms\Components\Textarea::make('hero_subtitle')->rows(3),
                    Forms\Components\TextInput::make('hero_cta_text')->maxLength(255),
                    Forms\Components\TextInput::make('hero_cta_url')->maxLength(255),
                    Forms\Components\FileUpload::make('hero_image_path')
                        ->disk('public')
                        ->directory('site/hero')
                        ->image(),
                    Forms\Components\FileUpload::make('hero_video_path')
                        ->disk('public')
                        ->directory('site/hero')
                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime']),
                ])
                ->columns(2),

            /*
            FormSection::make('Social Links')
                ->schema([
                    Forms\Components\Repeater::make('social_links')
                        ->schema([
                            Forms\Components\TextInput::make('label')->required(),
                            Forms\Components\TextInput::make('url')->required(),
                        ])
                        ->columns(2),
                ]),
            */
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand_name')->searchable(),
                Tables\Columns\TextColumn::make('hero_title')->searchable(),
                Tables\Columns\TextColumn::make('updated_at')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
