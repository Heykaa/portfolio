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
     * Safe singleton accessor (production friendly)
     */
    public static function instance(): self
    {
        $defaults = [
            'brand_name' => 'Portfolio',
            'hero_title' => 'Cinematic Portfolio',
        ];

        try {
            $model = new static;

            if (!Schema::hasTable($model->getTable())) {
                return new static($defaults);
            }

            return static::query()->firstOrCreate(
                ['id' => 1],
                $defaults
            );
        } catch (\Throwable $e) {
            return new static($defaults);
        }
    }
}
