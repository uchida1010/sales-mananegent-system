<?php

namespace App\Enums;

enum ClosingDay: string
{
    case Day10 = '10';
    case Day15 = '15';
    case Day20 = '20';
    case Day25 = '25';
    case EndOfMonth = 'end_of_month';

    public function label(): string
    {
        return match ($this) {
            self::Day10 => '10日',
            self::Day15 => '15日',
            self::Day20 => '20日',
            self::Day25 => '25日',
            self::EndOfMonth => '月末',
        };
    }
}
