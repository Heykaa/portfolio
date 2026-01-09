<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = [
        'brand_name',
        'hero_title',
        'hero_subtitle',
        'hero_cta_text',
        'hero_cta_url',
        'hero_image_path',
        'hero_video_path',
        'favicon_path',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    /**
     * Get singleton SiteSetting safely (won't crash if table not migrated yet)
     */
    public static function instance(): self
    {
        // Prevent crash if migration not run yet
        if (! Schema::hasTable('site_settings')) {
            return new self([
                'brand_name' => 'Portfolio',
                'hero_title' => 'Cinematic Portfolio',
            ]);
        }

        return self::query()->firstOrCreate([], [
            'brand_name' => 'Portfolio',
            'hero_title' => 'Cinematic Portfolio',
        ]);
    }
}
