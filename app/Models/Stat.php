<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $fillable = [
        'section_id',
        'label',
        'value',
        'suffix',
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
