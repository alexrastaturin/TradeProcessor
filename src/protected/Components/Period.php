<?php

namespace Components;


class Period
{
    const SEC = 1;
    const SEC5 = 5;
    const MIN = 60;
    const HOUR = 3600;

    public static function periods()
    {
        return [self::SEC, self::SEC5, self::MIN];
    }
}