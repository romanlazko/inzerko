<?php

namespace App\View\Components\Announcement;

use App\Models\Announcement;
use Illuminate\View\Component;
use Illuminate\View\View;

class Card extends Component
{
    public function __construct(private Announcement $announcement, public $layout = 'default')
    {
        
    }
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.announcement.'.($this->layout ?? 'default'), [
            'announcement' => $this->announcement
        ]);
    }
}
