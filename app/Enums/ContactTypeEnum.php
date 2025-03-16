<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum ContactTypeEnum: int implements HasLabel, HasColor, HasIcon
{
    // case EMAIL = 1;
    case PHONE = 2;
    case WHATSAPP = 3;
    case FACEBOOK = 4;
    case INSTAGRAM = 5;
    // case TELEGRAM = 6;
    case LINK = 7;

    // case MAIN_EMAIL = 8;

    public function getLabel(): ?string
    {
        return match ($this) {
            // self::EMAIL => 'Email',
            self::PHONE => 'Phone',
            self::WHATSAPP => 'WhatsApp',
            self::FACEBOOK => 'Facebook',
            self::INSTAGRAM => 'Instagram',
            // self::TELEGRAM => 'Telegram',
            self::LINK => 'Link',
            default => null,
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            // self::EMAIL => 'primary',
            self::PHONE => 'success',
            self::WHATSAPP => 'success',
            self::FACEBOOK => 'info',
            self::INSTAGRAM => 'pink',
            // self::TELEGRAM => 'info',
            self::LINK => 'primary',
            default => null,
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            // self::EMAIL => 'heroicon-s-at-symbol',
            self::PHONE => 'heroicon-s-phone',
            self::WHATSAPP => 'fab-whatsapp',
            self::FACEBOOK => 'fab-facebook',
            self::INSTAGRAM => 'fab-instagram',
            // self::TELEGRAM => 'fab-telegram',
            self::LINK => 'heroicon-s-link',
            default => null,
        };
    }

    public function getColorClass(): ?string
    {
        return match ($this) {
            // self::EMAIL => 'bg-indigo-600',
            self::PHONE => 'bg-green-600',
            self::WHATSAPP => 'bg-green-600',
            self::FACEBOOK => 'bg-blue-600',
            self::INSTAGRAM => 'bg-pink-600',
            // self::TELEGRAM => 'bg-blue-600',
            self::LINK => 'bg-indigo-600',
            default => null,
        };
    }

    public function getPrefix(): ?string
    {
        return match ($this) {
            // self::EMAIL => 'mailto:',
            self::PHONE => 'tel:',
            self::WHATSAPP => 'https://wa.me/',
            self::FACEBOOK => 'https://facebook.com/',
            self::INSTAGRAM => 'https://instagram.com/',
            // self::TELEGRAM => 'https://t.me/',
            self::LINK => 'https://',
            default => null,
        };
    }

    public function getRules(): ?string
    {
        return match ($this) {
            // self::EMAIL => 'email',
            self::PHONE => 'string',
            self::WHATSAPP => 'string',
            self::FACEBOOK => 'string',
            self::INSTAGRAM => 'string',
            // self::TELEGRAM => 'string',
            self::LINK => 'string',
            default => null,
        };
    }

    public function getHelperText()
    {
        return match ($this) {
            // self::EMAIL => __('helper text for email'),
            self::PHONE => __('helper text for phone'),
            self::WHATSAPP => __('helper text for whatsapp'),
            self::FACEBOOK => __('helper text for facebook'),
            self::INSTAGRAM => __('helper text for instagram'),
            // self::TELEGRAM => __('helper text for telegram'),
            self::LINK => __('helper text for link'),
            default => null,
        };
    }

    public function getPlaceholder()
    {
        return match ($this) {
            // self::EMAIL => __('Email address'),
            self::PHONE => __('Phone number to call'),
            self::WHATSAPP => __('Whatsapp username'),
            self::FACEBOOK => __('Facebook username'),
            self::INSTAGRAM => __('Instagram username'),
            // self::TELEGRAM => __('Telegram username'),
            self::LINK => __('Link'),
            default => null,
        };
    }
}