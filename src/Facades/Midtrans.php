<?php

namespace KhidirDotID\Midtrans\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \KhidirDotID\Midtrans getSnapToken($params)
 * @method static \KhidirDotID\Midtrans createTransaction($params)
 * @method static \KhidirDotID\Midtrans charge($params)
 * @method static \KhidirDotID\Midtrans capture($param)
 * @method static \KhidirDotID\Midtrans status($id)
 * @method static \KhidirDotID\Midtrans approve($id)
 * @method static \KhidirDotID\Midtrans cancel($id)
 * @method static \KhidirDotID\Midtrans expire($id)
 * @method static \KhidirDotID\Midtrans refund($id)
 * @method static \KhidirDotID\Midtrans deny($id)
 */
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
