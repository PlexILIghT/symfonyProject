<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class StockService
{
    public function getStock(int $id): string
    {
        return 'Stock id: ' . $id;
    }
}