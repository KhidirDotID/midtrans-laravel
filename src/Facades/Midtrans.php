<?php

namespace KhidirDotID\Midtrans\Facades;

use Illuminate\Support\Facades\Facade;

class Midtrans extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'midtrans';
    }
}
