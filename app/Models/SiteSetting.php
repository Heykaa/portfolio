<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Get the first record or create a default one.
     */
    public static function instance()
    {
        return self::firstOrCreate([], [
            'brand_name' => 'Portfolio',
            'hero_title' => 'Cinematic Portfolio',
        ]);
    }
}
