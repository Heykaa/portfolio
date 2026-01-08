<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Section;
use App\Models\CaseStudy;
use App\Models\Work;
use App\Models\Testimonial;
use App\Models\Stat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Site Settings
        SiteSetting::create([
            'brand_name' => 'CREATIVE.DEV',
            'hero_title' => 'CRAFTING DIGITAL',
            'hero_subtitle' => 'EXPERIENCES THAT MATTER',
            'hero_cta_text' => 'VIEW PROJECTS',
            'hero_cta_url' => '#work',
            'social_links' => [
                'twitter' => 'https://twitter.com',
                'linkedin' => 'https://linkedin.com',
                'github' => 'https://github.com',
            ],
        ]);

        // Sections
        $sections = [
            'hero' => ['title' => 'Hero Section', 'sort_order' => 0],
            'marquee' => ['title' => null, 'sort_order' => 1, 'data' => ['text' => 'AVAILABLE FOR FREELANCE • DESIGN DRIVEN • FULL-STACK DEVELOPMENT •']],
            'case_studies' => ['title' => 'FEATURED CASES', 'subtitle' => 'Deep dive into recent projects', 'sort_order' => 2, 'layout' => ['columns' => 1]],
            'selected_work' => ['title' => 'SELECTED WORK', 'subtitle' => 'A collection of digital crafts', 'sort_order' => 3, 'layout' => ['columns' => 3]],
            'stats' => ['title' => 'BY THE NUMBERS', 'sort_order' => 4],
            'stack3d' => ['title' => 'MY TECH STACK', 'sort_order' => 5],
            'testimonials' => ['title' => 'WHAT THEY SAY', 'sort_order' => 6],
            'contact' => ['title' => 'GET IN TOUCH', 'sort_order' => 7, 'data' => ['email' => 'hello@creative.dev']],
        ];

        $createdSections = [];
        foreach ($sections as $key => $data) {
            $createdSections[$key] = Section::create(array_merge(['key' => $key], $data));
        }

        // Case Studies
        $caseStudies = [
            ['title' => 'Visionary Brand Identity', 'caption' => 'Rebranding for a tech giant in Silicon Valley.', 'tags' => ['Branding', 'Motion']],
            ['title' => 'E-Commerce Evolution', 'caption' => 'A seamless shopping experience for modern retailers.', 'tags' => ['UX/UI', 'Magento']],
            ['title' => 'Architecture Visualizer', 'caption' => 'Photorealistic 3D rendering for urban projects.', 'tags' => ['3D Rendering', 'Visuals']],
        ];
        foreach ($caseStudies as $i => $cs) {
            $createdSections['case_studies']->caseStudies()->create(array_merge($cs, ['sort_order' => $i]));
        }

        // Works
        $works = [
            ['title' => 'Abstract Flow', 'tags' => ['Art']],
            ['title' => 'Cyber Glow', 'tags' => ['Web']],
            ['title' => 'Minimal Desk', 'tags' => ['Product']],
            ['title' => 'Urban Jungle', 'tags' => ['Photo']],
            ['title' => 'Neon Nights', 'tags' => ['Visual']],
            ['title' => 'Desert Echo', 'tags' => ['Design']],
            ['title' => 'Mountain Peak', 'tags' => ['Art']],
            ['title' => 'Ocean Drift', 'tags' => ['Web']],
            ['title' => 'Forest Rain', 'tags' => ['Sound']],
        ];
        foreach ($works as $i => $work) {
            $createdSections['selected_work']->works()->create(array_merge($work, ['sort_order' => $i]));
        }

        // Stats
        $stats = [
            ['label' => 'Projects Completed', 'value' => 120, 'suffix' => '+'],
            ['label' => 'Happy Clients', 'value' => 85, 'suffix' => ''],
            ['label' => 'Years of Experience', 'value' => 6, 'suffix' => 'Y'],
        ];
        foreach ($stats as $i => $stat) {
            $createdSections['stats']->stats()->create(array_merge($stat, ['sort_order' => $i]));
        }

        // Testimonials
        $testimonials = [
            ['quote' => 'Working with this team was a game changer for our digital presence.', 'name' => 'Sarah Johnson', 'role' => 'CEO, TechFlow'],
            ['quote' => 'Unbelievable attention to detail and cinematic feel.', 'name' => 'Michael Chen', 'role' => 'Lead Designer, StudioX'],
            ['quote' => 'The animations are smooth and the code is clean.', 'name' => 'Emma Davis', 'role' => 'CTO, Innovate'],
            ['quote' => 'Exceeded our expectations in every way possible.', 'name' => 'David Wilson', 'role' => 'Founder, StartupBase'],
        ];
        foreach ($testimonials as $i => $test) {
            $createdSections['testimonials']->testimonials()->create(array_merge($test, ['sort_order' => $i]));
        }
    }
}
