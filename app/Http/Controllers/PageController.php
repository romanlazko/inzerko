<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function show(Page $page)
    {
        if (! $page->is_active) {
            abort(404);
        }
    
        return response()->view('page', [
            'page' => $page
        ])
        ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }
}
