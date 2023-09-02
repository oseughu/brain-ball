<?php

namespace App\Services\TransferMarket;

use Illuminate\Support\Facades\Facade;

class TransferMarketFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TransferMarket::class;
    }
}
