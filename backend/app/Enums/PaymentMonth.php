<?php

namespace App\Enums;

enum PaymentMonth: string
{
    case NextMonth = 'next_month';
    case MonthAfterNext = 'month_after_next';

    public function label(): string
    {
        return match ($this) {
            self::NextMonth => '翌月',
            self::MonthAfterNext => '翌々月',
        };
    }
}
