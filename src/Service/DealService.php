<?php

use App\Entity\Application;
use App\Enums\ActionEnum;

class DealService
{
    public function __construct() {

    }

    public function findAppropriate() {
        
    }

    public function execute(Application $buyApplication, Application $sellApplication) : void
    {
        if ($buyApplication->getAction() === ActionEnum::SELL && $sellApplication->getAction() === ActionEnum::BUY) {
            
        }

    }
}