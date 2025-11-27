<?php

namespace common\components;

use DateTime;

class Formatter
{
    public function date(?DateTime $date): string
    {
        return $date ? $date->format('Y-m-d') : '-';
    }

    public function dateTime(?DateTime $date): string
    {
        return $date ? $date->format('Y-m-d H:i:s') : '-';
    }
}
