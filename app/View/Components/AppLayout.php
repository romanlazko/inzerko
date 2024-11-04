<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $meta = new SEOData(
            title: config('app.name'),
            description: config('app.description'),
            image: config('app.logo'),
            url: url()->current(),
            enableTitleSuffix: true,
            site_name: config('app.name'),
            locale: app()->getLocale(),
        );

        return view('layouts.app', compact('meta'));
    }
}
