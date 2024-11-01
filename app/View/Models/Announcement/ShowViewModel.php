<?php 

namespace App\View\Models\Announcement;

use App\Http\Requests\SearchRequest;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Announcement;
use App\Services\Actions\CategoryAttributeService;
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
        if (!$announcement->status->isPublished() AND $announcement->user?->id != auth()->id()) {
            abort(404, __('Announcement not found'));
        }

        return $announcement->load([
            'user.media',
            'media',
            'geo',
            'features:announcement_id,attribute_id,attribute_option_id,translated_value', 
            'features.attribute:id,name,alterlabels,is_feature,altersuffixes,alterprefixes,show_layout,group_layout',
            'features.attribute_option:id,alternames',
            'features.attribute.showSection:id,alternames,order_number,slug',
            'features.attribute.group:id,slug,separator',
            'category:id,slug,alternames,parent_id',
        ]);
    }

    private function similar_announcements()
    {
        return Announcement::with([
                'media',
                'features' => fn ($query) => $query->forAnnouncementCard(),
                'geo',
                'userVotes',
            ])
            ->select('announcements.id', 'announcements.slug', 'announcements.geo_id', 'announcements.created_at')
            ->isPublished()
            ->where('announcements.id', '!=', $this->announcement->id)
            ->whereHas('categories', fn ($query) => $query->whereIn('categories.slug', $this->announcement->categories->pluck('slug')->toArray()))
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
            ->isPublished()
            ->where('announcements.id', '!=', $this->announcement->id)
            ->where('announcements.user_id', auth()->id())
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