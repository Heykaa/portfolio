<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'key',
        'title',
        'subtitle',
        'enabled',
        'sort_order',
        'layout',
        'data',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'layout' => 'array',
        'data' => 'array',
    ];

    public function caseStudies()
    {
        return $this->hasMany(CaseStudy::class)->orderBy('sort_order');
    }

    public function works()
    {
        return $this->hasMany(Work::class)->orderBy('sort_order');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class)->orderBy('sort_order');
    }

    public function stats()
    {
        return $this->hasMany(Stat::class)->orderBy('sort_order');
    }
}
