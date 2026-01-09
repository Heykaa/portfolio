<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
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

    public static function instance(): self
    {
        // If table not migrated yet, return in-memory defaults (avoid 500 during boot)
        if (! Schema::hasTable('site_settings')) {
            $model = new self();
            $model->fill([
                'brand_name' => 'Portfolio',
                'hero_title' => 'Cinematic Portfolio',
                'social_links' => [],
            ]);
            return $model;
        }

        try {
            return self::query()->firstOrCreate(
                [],
                [
                    'brand_name' => 'Portfolio',
                    'hero_title' => 'Cinematic Portfolio',
                    'social_links' => [],
                ]
            );
        } catch (QueryException) {
            // Safety fallback
            $model = new self();
            $model->fill([
                'brand_name' => 'Portfolio',
                'hero_title' => 'Cinematic Portfolio',
                'social_links' => [],
            ]);
            return $model;
        }
    }
}
