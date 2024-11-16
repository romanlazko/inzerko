<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\App;

enum CardLayout: int implements HasLabel
{
    case default = 1;
    case no_media_card = 2;
    case descriotion_insead_media = 3;

    public function getLabel() : ?string
    {
        return $this->name;
    }
}