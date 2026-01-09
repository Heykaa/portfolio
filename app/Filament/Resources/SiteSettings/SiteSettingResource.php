<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

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
     * Get the first record or create a default one.
     * SAFE in production even if migrations haven't run yet.
     */
    public static function instance(): self
    {
        // If table doesn't exist yet, return an in-memory default model (no DB hit).
        try {
            if (! Schema::hasTable('site_settings')) {
                return new self([
                    'brand_name' => config('app.name', 'Portfolio'),
                    'hero_title' => 'Cinematic Portfolio',
                ]);
            }
        } catch (\Throwable $e) {
            return new self([
                'brand_name' => config('app.name', 'Portfolio'),
                'hero_title' => 'Cinematic Portfolio',
            ]);
        }

        return self::query()->firstOrCreate(
            ['id' => 1],
            [
                'brand_name' => config('app.name', 'Portfolio'),
                'hero_title' => 'Cinematic Portfolio',
            ]
        );
    }
}
