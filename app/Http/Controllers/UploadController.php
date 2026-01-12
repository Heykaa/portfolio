<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Stream an uploaded file from the `uploads` disk.
     * Avoids relying on public/storage symlink.
     */
    public function show(Request $request, string $path)
    {
        // Basic traversal protection
        if (str_contains($path, '..') || str_starts_with($path, '/')) {
            abort(404);
        }

        $disk = Storage::disk('uploads');

        if (! $disk->exists($path)) {
            abort(404);
        }

        // Stream response + cache for public assets
        return $disk->response($path, null, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
