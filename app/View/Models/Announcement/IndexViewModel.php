<?php 

namespace App\View\Models\Announcement;

use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Announcement;
use App\Services\Actions\CategoryAttributeService;
use Illuminate\Support\Facades\Cache;

class IndexViewModel
{
    public $category = null;

    public $categories = null;

    public $announcements = null;

    public $sortings = null;

    public function __construct(public SearchRequest $request)
    {
        $this->category = $this->category();
        $this->categories = $this->categories();
        $this->announcements = $this->announcements();
        $this->sortings = $this->sortings();
    }

    private function category(): ?Category
    {
        if (!$this->request->route('category')) {
            return null;
        }

        return Cache::remember($this->request->route('category').'_category', config('cache.ttl'), function () {
            return Category::select('id', 'slug', 'parent_id', 'is_active', 'alternames')
                ->where('slug', $this->request->route('category'))
                ->isActive()
                ->first();
        });
    }

    private function categories()
    {
        return Cache::remember($this->category?->slug.'_categories', config('cache.ttl'), function () {
            if ($children = $this->category?->children->where('is_active', true) AND $children->isNotEmpty()) {
                return $children->load('media');
            }

            if ($siblings = $this->category?->siblings->where('is_active', true) AND $siblings->isNotEmpty()) {
                return $siblings->load('media');
            }

            return Category::whereNull('parent_id')
                ->isActive()
                ->get()
                ->load('media');
        });
    }

    private function announcements()
    {
        return Announcement::with([
                'media',
                'features' => fn ($query) => $query->forAnnouncementCard(),
                'geo',
                'votes' =>  fn ($query) => $query->where('user_id', auth()->id()),
            ])
            ->category($this->category)
            ->sort($this->request->sort)
            ->geo($this->request->location)
            ->filter($this->request->filters['attributes'] ?? [], $this->category)
            ->search($this->request->search)
            ->isPublished()
            ->paginate(30)
            ->withQueryString();
    }

    private function sortings()
    {
        return CategoryAttributeService::forSorting($this->category);
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function getSortings()
    {
        return $this->sortings;
    }

    public function getAnnouncements()
    {
        return $this->announcements;
    }

    public function getPaginator()
    {
        return $this->announcements;
    }
}