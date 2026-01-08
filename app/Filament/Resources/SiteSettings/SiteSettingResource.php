<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use BackedEnum;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return SiteSetting::count() === 0;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('brand_name')
                                            ->required()
                                            ->default('Portfolio')
                                            ->columnSpan(1),
                                        FileUpload::make('favicon_path')
                                            ->label('Favicon')
                                            ->image()
                                            ->avatar()
                                            ->disk('public')
                                            ->directory('site')
                                            ->visibility('public')
                                            ->imageEditor()
                                            ->columnSpan(1),
                                    ]),
                            ]),
                        Tabs\Tab::make('Hero Section')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('hero_title')
                                        ->label('Main Title')
                                        ->placeholder('e.g., CRAFTING DIGITAL'),
                                    TextInput::make('hero_subtitle')
                                        ->label('Subtitle')
                                        ->placeholder('e.g., YOUR NAME'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('hero_cta_text')
                                        ->label('CTA Button Text')
                                        ->default('VIEW PROJECTS'),
                                    TextInput::make('hero_cta_url')
                                        ->label('CTA Button URL')
                                        ->helperText('Full URL (https://...) or Section ID (#work)'),
                                ]),
                                Grid::make(2)->schema([
                                    FileUpload::make('hero_image_path')
                                        ->label('Hero Image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('site')
                                        ->visibility('public')
                                        ->imageEditor()
                                        ->columnSpan(1),
                                    FileUpload::make('hero_video_path')
                                        ->label('Hero Video')
                                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                                        ->disk('public')
                                        ->directory('site')
                                        ->visibility('public')
                                        ->maxSize(51200) // 50MB
                                        ->helperText('Max size: 50MB. Formats: MP4, WebM, OGG.')
                                        ->columnSpan(1),
                                ]),
                            ]),
                        Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                KeyValue::make('social_links')
                                    ->keyLabel('Platform Name')
                                    ->valueLabel('Profile URL')
                                    ->addButtonLabel('Add Social Link')
                                    ->reorderable(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('brand_name')
                    ->searchable(),
                TextColumn::make('hero_title')
                    ->searchable(),
                TextColumn::make('hero_subtitle')
                    ->searchable(),
                TextColumn::make('hero_cta_text')
                    ->searchable(),
                TextColumn::make('hero_cta_url')
                    ->searchable(),
                ImageColumn::make('hero_image_path')
                    ->label('Image'),
                TextColumn::make('hero_video_path')
                    ->label('Video')
                    ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->slideOver(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSiteSettings::route('/'),
        ];
    }
}
