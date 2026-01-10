<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Section;

class PortfolioController extends Controller
{
    public function index()
    {
        // ✅ Ambil settings (fallback empty object kalau null)
        $settings = SiteSetting::instance() ?? (object) [];

        // ✅ Ambil sections yang enable sahaja
        $sections = Section::with(['caseStudies', 'works', 'testimonials', 'stats'])
            ->where('enabled', true)
            ->orderBy('sort_order')
            ->get();

        return view('portfolio', compact('settings', 'sections'));
    }
}
