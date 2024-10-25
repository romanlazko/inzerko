<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Wishlist extends Controller
{
    public function index()
    {
        $announcements = auth()->user()->wishlist()->isPublished()->latest()
            ->paginate(30)->withQueryString();

        return view('profile.wishlist', compact('announcements'));
    }
}
