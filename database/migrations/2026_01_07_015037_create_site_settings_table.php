<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_cta_text')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->string('hero_image_path')->nullable();
            $table->string('hero_video_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        // Create default row id=1 (so instance() consistent)
        DB::table('site_settings')->insert([
            'id' => 1,
            'brand_name' => config('app.name', 'Portfolio'),
            'hero_title' => 'Cinematic Portfolio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
