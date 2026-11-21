<?php

namespace App\Enums;

enum DueType: string
{
    case Advance = 'advance';
    case ThreeBusinessDays = 'three_business_days';
    case SevenBusinessDays = 'seven_business_days';
    case Immediate = 'immediate';

    public function label(): string
    {
        return match ($this) {
            self::Advance => '前金',
            self::ThreeBusinessDays => '3営業日',
            self::SevenBusinessDays => '7営業日',
            self::Immediate => '都度／即時',
        };
    }
}
