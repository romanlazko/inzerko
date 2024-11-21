<?php 

namespace App\View\Models;

use App\AttributeType\AttributeFactory;
use App\Http\Requests\SearchRequest;
use App\Models\Announcement;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Feature;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeViewModel
{
    public function getCategories()
    {
        return Cache::remember('all_categories', config('cache.ttl'), function () {
            return Category::whereNull('parent_id')
                ->isActive()
                ->get()
                ->load('media');
        });
    }

    public function getAnnouncements()
    {
        return Announcement::with([
                'media',
                'features' => fn ($query) => $query->forAnnouncementCard(),
                'geo',
                'votes' =>  fn ($query) => $query->where('user_id', auth()->id()),
                'category.media'
            ])
            ->select('announcements.id', 'announcements.slug', 'announcements.geo_id', 'announcements.created_at', 'announcements.category_id')
            ->isPublished()
            ->orderByDesc('announcements.created_at')
            ->paginate(30)
            ->withQueryString();
    }
}