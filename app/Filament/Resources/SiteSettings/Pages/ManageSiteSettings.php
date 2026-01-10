<?php

namespace App\Filament\Resources\SiteSettings\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteSettings extends ManageRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function canCreate(): bool
    {
        return false;
    }
}
