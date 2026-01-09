use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

public function panel(\Filament\Panel $panel): \Filament\Panel
{
    $brand = config('app.name', 'Portfolio');

    try {
        if (Schema::hasTable('site_settings')) {
            $settings = SiteSetting::query()->find(1);
            $brand = $settings?->brand_name ?: $brand;
        }
    } catch (\Throwable $e) {
        // ignore - keep default brand
    }

    return $panel
        ->brandName($brand)
        // ...kekalkan config panel tu yang lain
        ;
}
