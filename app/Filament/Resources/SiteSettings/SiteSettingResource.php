<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;
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

            /**
             * ---------------------------------------------------------
             * BRAND
             * ---------------------------------------------------------
             */
            FormSection::make('Brand')
                ->schema([
                    TextInput::make('brand_name')
                        ->label('Brand Name')
                        ->maxLength(255),

                    FileUpload::make('favicon_path')
                        ->label('Favicon (.ico / .png / .svg)')
                        ->disk('local')
                        ->directory('uploads/site')
                        ->visibility('private')
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->acceptedFileTypes([
                            'image/x-icon',
                            'image/vnd.microsoft.icon',
                            'image/png',
                            'image/svg+xml',
                        ])
                        ->maxSize(256), // KB
                ])
                ->columns(2),

            /**
             * ---------------------------------------------------------
             * HERO SECTION
             * ---------------------------------------------------------
             */
            FormSection::make('Hero')
                ->schema([
                    TextInput::make('hero_title')
                        ->label('Hero Title')
                        ->maxLength(255),

                    Textarea::make('hero_subtitle')
                        ->label('Hero Subtitle')
                        ->rows(3),

                    TextInput::make('hero_cta_text')
                        ->label('CTA Text')
                        ->maxLength(255),

                    TextInput::make('hero_cta_url')
                        ->label('CTA URL')
                        ->maxLength(255),

                    FileUpload::make('hero_image_path')
                        ->label('Hero Image')
                        ->image()
                        ->disk('local')
                        ->directory('uploads/site/hero')
                        ->visibility('private')
                        ->preserveFilenames()
                        ->imageEditor()
                        ->downloadable()
                        ->openable()
                        ->maxSize(4096), // 4MB

                    FileUpload::make('hero_video_path')
                        ->label('Hero Video (mp4 / webm / mov)')
                        ->disk('local')
                        ->directory('uploads/site/hero')
                        ->visibility('private')
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable()
                        ->acceptedFileTypes([
                            'video/mp4',
                            'video/webm',
                            'video/quicktime',
                        ])
                        ->maxSize(20480), // 20MB
                ])
                ->columns(2),

            /**
             * ---------------------------------------------------------
             * SOCIAL LINKS
             * ---------------------------------------------------------
             */
            FormSection::make('Social Links')
                ->schema([
                    Repeater::make('social_links')
                        ->schema([
                            TextInput::make('label')
                                ->required(),
                            TextInput::make('url')
                                ->required(),
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
            ->recordActions([
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
