<?php

namespace App\Filament\Resources\SiteSettings;

use App\Filament\Resources\SiteSettings\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $pluralLabel = 'Site Settings';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('brand_name')->required()->maxLength(255),

                TextInput::make('hero_title')->required()->maxLength(255),
                TextInput::make('hero_subtitle')->required()->maxLength(255),

                TextInput::make('hero_cta_text')->required()->maxLength(255),
                TextInput::make('hero_cta_url')->required()->maxLength(255),

                FileUpload::make('hero_image_path')
                    ->label('Hero Image')
                    ->image()
                    ->disk('public')
                    ->directory('hero')
                    ->visibility('public'),

                FileUpload::make('hero_video_path')
                    ->label('Hero Video (MP4)')
                    ->disk('public')
                    ->directory('hero')
                    ->visibility('public')
                    ->acceptedFileTypes(['video/mp4']),

                KeyValue::make('social_links')
                    ->keyLabel('Platform')
                    ->valueLabel('URL')
                    ->addable()
                    ->deletable()
                    ->reorderable(),
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
            // Filament v4: guna recordActions, bukan actions()
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
