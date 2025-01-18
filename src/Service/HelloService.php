<?php

namespace App\Service;

use http\Env\Response;

class HelloService
{
    public function  generateLuckyNumber(): string
    {
        return rand(1, 3);
    }
}