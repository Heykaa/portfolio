<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SiteSetting;
use App\Models\Section;

class PortfolioController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::instance();

        $sections = Section::with(['caseStudies', 'works', 'testimonials', 'stats'])
            ->where('enabled', true)
            ->orderBy('sort_order')
            ->get();

        return view('portfolio', compact('settings', 'sections'));
    }
}
