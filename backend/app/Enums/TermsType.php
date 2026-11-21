<?php

namespace App\Enums;

enum TermsType: string
{
    case Closing = 'closing';
    case EachTime = 'each_time';

    public function label(): string
    {
        return match ($this) {
            self::Closing => '締め請求',
            self::EachTime => '都度請求',
        };
    }
}
