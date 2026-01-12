<?php

namespace App\Filament\Resources\SiteSettings\Pages;

use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Resources\Pages\ListRecords;

class ManageSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(): void
    {
        parent::mount();

        // ✅ ensure singleton exists
        if (! SiteSetting::query()->exists()) {
            SiteSetting::query()->create([
                'brand_name' => 'CREATIVE.DEV',
                'social_links' => [],
            ]);
        }
    }
}
