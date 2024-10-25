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

        return view('announcement.index', [
            'announcements' => $viewModel->getAnnouncements(),
            'categories' => $viewModel->getCategories(),
            'category' => $viewModel->getCategory(),
            'sortings' => $viewModel->getSortings(),
            'paginator' => $viewModel->getPaginator(),
            'request' => $request,
        ]);
    }

    public function search(SearchRequest $request)
    {
        return redirect()->route('announcement.index', [
            'category' => $request->route('category'),
            'data'   => $request->serializedData(),
        ]);
    }

    public function show(Announcement $announcement)
    {
        $viewModel = new ShowViewModel($announcement);

        return view('announcement.show', [
            'announcement' => $viewModel->getAnnouncement(),
            'similar_announcements' => $viewModel->getSimilarAnnouncements(),
            'user_announcements' => $viewModel->getUserAnnouncements(),
        ]);
    }

    public function create()
    {
        return view('announcement.create');
    }
}
