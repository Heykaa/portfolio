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
     * Get singleton SiteSetting safely (Render Free compatible)
     */
    public static function instance(): self
    {
        // 🔴 IMPORTANT: prevent crash if migration not run yet
        if (! Schema::hasTable('site_settings')) {
            return new self([
                'brand_name' => 'Portfolio's,
                'hero_title' => 'Cinematic Portfolio',
            ]);
        }

        return self::firstOrCreate([], [
            'brand_name' => 'Portfolio',
            'hero_title' => 'Cinematic Portfolio',
        ]);
    }
}
