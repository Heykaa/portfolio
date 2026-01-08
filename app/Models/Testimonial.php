<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'section_id',
        'quote',
        'name',
        'role',
        'avatar_path',
        'sort_order',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
