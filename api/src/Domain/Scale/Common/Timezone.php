<?php

namespace Src\Domain\Scale\Common;

use DateTime;
use DateTimeZone;

final class Timezone
{
    public static function dateToTz(string $date, string $timezone)
    {
        ($timezone !== '') 
            ? $dt = new DateTime($date, new DateTimeZone($timezone))
            : $dt = new DateTime($date);
        $dt->setTimezone(new DateTimeZone('UTC'));
        return $dt->format('Y-m-d H:i:s');
    }    
}