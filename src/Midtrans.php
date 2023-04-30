<?php

namespace KhidirDotID\Midtrans;

use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Snap;
use Midtrans\Transaction;

class Midtrans
{
    public function __construct()
    {
        self::registerMidtransConfig();
    }

    public static function registerMidtransConfig()
    {
        // Set your Merchant Server Key
        $isProduction = config('midtrans.is_production');
        Config::$serverKey = $isProduction ? config('midtrans.production_server_key') : config('midtrans.sandbox_server_key');
        Config::$clientKey = $isProduction ? config('midtrans.production_client_key') : config('midtrans.sandbox_client_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = $isProduction;
        // Set sanitization on (default)
        Config::$isSanitized = config('midtrans.is_sanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('midtrans.is_3ds');
        // Add new notification url(s) alongside the settings on Midtrans Dashboard Portal (MAP)
        Config::$appendNotifUrl = config('midtrans.append_notif_url');
        // Use new notification url(s) disregarding the settings on Midtrans Dashboard Portal (MAP)
        Config::$overrideNotifUrl = config('midtrans.override_notif_url');
        // more details: (http://api-docs.midtrans.com/#idempotent-requests)
        Config::$paymentIdempotencyKey = config('midtrans.payment_idempotency_key');

        Config::$curlOptions = config('midtrans.curl_options');
    }

    /**
     * Your merchant's server key
     *
     * @static
     */
    public static $serverKey;

    /**
     * Your merchant's client key
     *
     * @static
     */
    public static $clientKey;

    /**
     * True for production
     * false for sandbox mode
     *
     * @static
     */
    public static $isProduction = false;

    /**
     * Set it true to enable 3D Secure by default
     *
     * @static
     */
    public static $is3ds = false;

    /**
     *  Set Append URL notification
     *
     * @static
     */
    public static $appendNotifUrl;

    /**
     *  Set Override URL notification
     *
     * @static
     */
    public static $overrideNotifUrl;

    /**
     *  Set Payment IdempotencyKey
     *  for details (http://api-docs.midtrans.com/#idempotent-requests)
     *
     * @static
     */
    public static $paymentIdempotencyKey;

    /**
     * Enable request params sanitizer (validate and modify charge request params).
     * See Midtrans_Sanitizer for more details
     *
     * @static
     */
    public static $isSanitized = false;

    /**
     * Default options for every request
     *
     * @static
     */
    public static $curlOptions = array();

    /**
     * Create Snap payment page
     *
     * Example:
     *
     * ```php
     *
     *   namespace Midtrans;
     *
     *   $params = array(
     *     'transaction_details' => array(
     *       'order_id' => rand(),
     *       'gross_amount' => 10000,
     *     )
     *   );
     *   $paymentUrl = Snap::getSnapToken($params);
     * ```
     *
     * @param  array $params Payment options
     * @return string Snap token.
     * @throws Exception curl error or midtrans error
     */
    public static function getSnapToken($params)
    {
        return Snap::getSnapToken($params);
    }

    /**
     * Create Snap payment page, with this version returning full API response
     *
     * Example:
     *
     * ```php
     *   $params = array(
     *     'transaction_details' => array(
     *       'order_id' => rand(),
     *       'gross_amount' => 10000,
     *     )
     *   );
     *   $paymentUrl = Snap::getSnapToken($params);
     * ```
     *
     * @param  array $params Payment options
     * @return object Snap response (token and redirect_url).
     * @throws Exception curl error or midtrans error
     */
    public static function createTransaction($params)
    {
        return Snap::createTransaction($params);
    }

    /**
     * Create transaction.
     *
     * @param mixed[] $params Transaction options
     * @return mixed
     * @throws Exception
     */
    public static function charge($params)
    {
        return CoreApi::charge($params);
    }

    /**
     * Capture pre-authorized transaction
     *
     * @param string $param Order ID or transaction ID, that you want to capture
     * @return mixed
     * @throws Exception
     */
    public function capture($param)
    {
        return CoreApi::capture($param);
    }

    /**
     * Retrieve transaction status
     *
     * @param string $id Order ID or transaction ID
     *
     * @return mixed[]
     * @throws Exception
     */
    public function status($id)
    {
        return Transaction::status($id);
    }

    /**
     * Approve challenge transaction
     *
     * @param string $id Order ID or transaction ID
     *
     * @return string
     * @throws Exception
     */
    public function approve($id)
    {
        return Transaction::approve($id);
    }

    /**
     * Cancel transaction before it's settled
     *
     * @param string $id Order ID or transaction ID
     *
     * @return string
     * @throws Exception
     */
    public function cancel($id)
    {
        return Transaction::cancel($id);
    }

    /**
     * Expire transaction before it's setteled
     *
     * @param string $id Order ID or transaction ID
     *
     * @return mixed[]
     * @throws Exception
     */
    public function expire($id)
    {
        return Transaction::expire($id);
    }

    /**
     * Transaction status can be updated into refund
     * if the customer decides to cancel completed/settlement payment.
     * The same refund id cannot be reused again.
     *
     * @param string $id Order ID or transaction ID
     *
     * @param $params
     * @return mixed[]
     * @throws Exception
     */
    public function refund($id)
    {
        return Transaction::refund($id);
    }

    /**
     * Deny method can be triggered to immediately deny card payment transaction
     * in which fraud_status is challenge.
     *
     * @param string $id Order ID or transaction ID
     *
     * @return mixed[]
     * @throws Exception
     */
    public function deny($id)
    {
        return Transaction::deny($id);
    }
}
