<?php

namespace App\Service;

class DateTimeService
{
    public function getCurrentDateTime(): string
    {
        date_default_timezone_set('Asia/Vladivostok');
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}