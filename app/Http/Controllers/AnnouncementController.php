<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Announcement;
use App\View\Models\Announcement\IndexViewModel;
use App\View\Models\Announcement\ShowViewModel;

class AnnouncementController extends Controller
{
    public function index(SearchRequest $request)
    {
        $viewModel = new IndexViewModel($request);

        return response()->view('announcement.index', [
            'announcements' => $viewModel->getAnnouncements(),
            'categories' => $viewModel->getCategories(),
            'category' => $viewModel->getCategory(),
            'sortings' => $viewModel->getSortings(),
            'paginator' => $viewModel->getPaginator(),
            'request' => $request,
        ])
        ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }

    public function search(SearchRequest $request)
    {
        return redirect()->route('announcement.index', [
            'category' => $request->route('category'),
            'data'   => $request->serializedData(),
        ])
        ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }

    public function show(Announcement $announcement)
    {
        $viewModel = new ShowViewModel($announcement);

        return response()->view('announcement.show', [
            'announcement' => $viewModel->getAnnouncement(),
            'similar_announcements' => $viewModel->getSimilarAnnouncements(),
            'user_announcements' => $viewModel->getUserAnnouncements(),
        ])
        ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }

    public function create()
    {
        return response()
            ->view('announcement.create')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }
}
