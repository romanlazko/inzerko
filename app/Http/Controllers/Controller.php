<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\SearchRequest;
use App\View\Models\HomeViewModel;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function locale(Request $request)
    {
        $request->validate([
            'locale' => ['required', 'string', 'in:'.implode(',', array_keys(config('translate.languages')))],
        ]);

        if ($user = auth()->user()) {
            $user->update([
                'locale' => $request->locale
            ]);
        }

        session(['locale' => $request->locale]);

        return redirect()->back();
    }

    public function home(SearchRequest $request)
    {
        session()->forget('filters');
        session()->forget('search');
        session()->forget('sort');

        $viewModel = new HomeViewModel();

        return response()->view('home', [
            'announcements' => $viewModel->getAnnouncements(),
            'categories' => $viewModel->getCategories(),
            'request' => $request,
        ])
        ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }
}
