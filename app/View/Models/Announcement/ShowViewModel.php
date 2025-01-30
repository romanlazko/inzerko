<?php 

namespace App\View\Models\Announcement;

use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Announcement;
use App\Services\Actions\AttributesByCategoryService;
use Illuminate\Support\Facades\Cache;

class ShowViewModel
{
    private $announcement; 
    private $similar_announcements = []; 
    private $user_announcements = [];

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $this->announcement($announcement);
        // $this->similar_announcements = $this->similar_announcements();
        // $this->user_announcements = $this->user_announcements();
    }

    private function announcement($announcement)
    {
        if ($announcement->status->isSold() OR !$announcement->is_active) {
            abort(404, 'Announcement is no longer relevant');
        }

        if (! $announcement->status->isPublished()) {
            abort(404, 'Announcement is no longer relevant');
        }

        return $announcement->load([
            'user.media',
            'user.votes',
            'media',
            'geo',
            'features:announcement_id,attribute_id,attribute_option_id,translated_value', 
            'features.attribute:id,name,alterlabels,is_feature,altersuffixes,alterprefixes,show_layout,group_layout,is_active',
            'features.attribute_option:id,alternames',
            'category:id,slug,alternames,parent_id',
        ]);
    }

    private function similar_announcements()
    {
        return Announcement::with([
                'media',
                'features' => fn ($query) => $query->forAnnouncementCard(),
                'geo',
            ])
            ->select('announcements.id', 'announcements.slug', 'announcements.geo_id', 'announcements.created_at')
            ->where('announcements.id', '!=', $this->announcement->id)
            ->whereHas('categories', fn ($query) => $query->whereIn('categories.slug', $this->announcement->categories->pluck('slug')->toArray()))
            ->isPublished()
            ->isActive()
            ->orderByDesc('announcements.created_at')
            ->limit(10)
            ->get();
    }

    private function user_announcements()
    {
        return Announcement::with([
                'media',
                'features' => fn ($query) => $query->forAnnouncementCard(),
                'geo',
                'userVotes',
            ])
            ->select('announcements.id', 'announcements.slug', 'announcements.geo_id', 'announcements.created_at')
            ->where('announcements.id', '!=', $this->announcement->id)
            ->where('announcements.user_id', auth()->id())
            ->isPublished()
            ->isActive()
            ->orderByDesc('announcements.created_at')
            ->limit(10)
            ->get();
    }

    public function getAnnouncement()
    {
        return $this->announcement;
    }

    public function getSimilarAnnouncements()
    {
        return $this->similar_announcements;
    }

    public function getUserAnnouncements()
    {
        return $this->user_announcements;
    }

}