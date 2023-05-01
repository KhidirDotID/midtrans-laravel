<?php

namespace KhidirDotID\Midtrans\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void setServerKey(string $serverKey)
 * @method static void setClientKey(string $clientKey)
 * @method static void setProduction(bool $isProduction)
 * @method static void set3ds(bool $is3ds)
 * @method static void setAppendNotifUrl(string $appendNotifUrl)
 * @method static void setOverrideNotifUrl(string $overrideNotifUrl)
 * @method static void setPaymentIdempotencyKey(string $paymentIdempotencyKey)
 * @method static void setSanitized(bool $isSanitized)
 * @method static void setCurlOptions(array $curlOptions)
 * @method static string getSnapToken(array $params)
 * @method static string getSnapUrl(array $params)
 * @method static mixed createTransaction(array $params)
 * @method static mixed charge(array $params)
 * @method static mixed capture(string $param)
 * @method static mixed cardRegister(string $cardNumber, string $expMoth, string $expYear)
 * @method static mixed cardToken(string $cardNumber, string $expMoth, string $expYear, string $cvv)
 * @method static mixed cardPointInquiry(string $tokenId)
 * @method static mixed linkPaymentAccount(array $params)
 * @method static mixed getPaymentAccount(string $accountId)
 * @method static mixed unlinkPaymentAccount(string $accountId)
 * @method static mixed createSubscription(array $params)
 * @method static mixed getSubscription(string $subscriptionId)
 * @method static mixed disableSubscription(string $subscriptionId)
 * @method static mixed enableSubscription(string $subscriptionId)
 * @method static mixed updateSubscription(string $subscriptionId, array $params)
 * @method static mixed status(string $id)
 * @method static mixed statusB2b(string $id)
 * @method static string approve(string $id)
 * @method static string cancel(string $id)
 * @method static mixed expire(string $id)
 * @method static mixed refund(string $id, array $params)
 * @method static mixed refundDirect(string $id, array $params)
 * @method static mixed deny(string $id)
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
