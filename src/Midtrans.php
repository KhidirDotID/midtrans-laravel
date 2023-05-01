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
     * Set your merchant's server key
     *
     * @static
     */
    public static function setServerKey($serverKey)
    {
        Config::$serverKey = $serverKey;
    }

    /**
     * Set your merchant's client key
     *
     * @static
     */
    public static function setClientKey($clientKey)
    {
        Config::$clientKey = $clientKey;
    }

    /**
     * True for production
     * false for sandbox mode
     *
     * @static
     */
    public static function setProduction($isProduction)
    {
        Config::$isProduction = $isProduction;
    }

    /**
     * Set it true to enable 3D Secure by default
     *
     * @static
     */
    public static function set3ds($is3ds)
    {
        Config::$is3ds = $is3ds;
    }

    /**
     *  Set Append URL notification
     *
     * @static
     */
    public static function setAppendNotifUrl($appendNotifUrl)
    {
        Config::$appendNotifUrl = $appendNotifUrl;
    }

    /**
     *  Set Override URL notification
     *
     * @static
     */
    public static function setOverrideNotifUrl($overrideNotifUrl)
    {
        Config::$overrideNotifUrl = $overrideNotifUrl;
    }

    /**
     *  Set Payment IdempotencyKey
     *  for details (http://api-docs.midtrans.com/#idempotent-requests)
     *
     * @static
     */
    public static function setPaymentIdempotencyKey($paymentIdempotencyKey)
    {
        Config::$paymentIdempotencyKey = $paymentIdempotencyKey;
    }

    /**
     * Enable request params sanitizer (validate and modify charge request params).
     * See Midtrans_Sanitizer for more details
     *
     * @static
     */
    public static function setSanitized($isSanitized)
    {
        Config::$isSanitized = $isSanitized;
    }

    /**
     * Default options for every request
     *
     * @static
     */
    public static function setCurlOptions($curlOptions)
    {
        Config::$curlOptions = $curlOptions;
    }

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
     * Create Snap URL payment
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
     *   $paymentUrl = Snap::getSnapUrl($params);
     * ```
     *
     * @param  array $params Payment options
     * @return string Snap redirect url.
     * @throws Exception curl error or midtrans error
     */
    public static function getSnapUrl($params)
    {
        return Snap::getSnapUrl($params);
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
    public static function capture($transactionId)
    {
        return CoreApi::capture($transactionId);
    }

    /**
     * Do `/v2/card/register` API request to Core API
     *
     * @param $cardNumber
     * @param $expMoth
     * @param $expYear
     * @return mixed
     * @throws Exception
     */
    public static function cardRegister($cardNumber, $expMoth, $expYear)
    {
        return CoreApi::cardRegister($cardNumber, $expMoth, $expYear);
    }

    /**
     * Do `/v2/token` API request to Core API
     *
     * @param $cardNumber
     * @param $expMoth
     * @param $expYear
     * @param $cvv
     * @return mixed
     * @throws Exception
     */
    public static function cardToken($cardNumber, $expMoth, $expYear, $cvv)
    {
        return CoreApi::cardToken($cardNumber, $expMoth, $expYear, $cvv);
    }

    /**
     * Do `/v2/point_inquiry/<tokenId>` API request to Core API
     *
     * @param string tokenId - tokenId of credit card (more params detail refer to: https://api-docs.midtrans.com)
     * @return mixed
     * @throws Exception
     */
    public static function cardPointInquiry($tokenId)
    {
        return CoreApi::cardPointInquiry($tokenId);
    }

    /**
     * Create `/v2/pay/account` API request to Core API
     *
     * @param string create pay account request (more params detail refer to: https://api-docs.midtrans.com/#create-pay-account)
     * @return mixed
     * @throws Exception
     */
    public static function linkPaymentAccount($params)
    {
        return CoreApi::linkPaymentAccount($params);
    }

    /**
     * Do `/v2/pay/account/<accountId>` API request to Core API
     *
     * @param string accountId (more params detail refer to: https://api-docs.midtrans.com/#get-pay-account)
     * @return mixed
     * @throws Exception
     */
    public static function getPaymentAccount($accountId)
    {
        return CoreApi::getPaymentAccount($accountId);
    }

    /**
     * Unbind `/v2/pay/account/<accountId>/unbind` API request to Core API
     *
     * @param string accountId (more params detail refer to: https://api-docs.midtrans.com/#unbind-pay-account)
     * @return mixed
     * @throws Exception
     */
    public static function unlinkPaymentAccount($accountId)
    {
        return CoreApi::unlinkPaymentAccount($accountId);
    }

    /**
     * Create `/v1/subscription` API request to Core API
     *
     * @param string create subscription request (more params detail refer to: https://api-docs.midtrans.com/#create-subscription)
     * @return mixed
     * @throws Exception
     */
    public static function createSubscription($params)
    {
        return CoreApi::createSubscription($params);
    }

    /**
     * Do `/v1/subscription/<subscription_id>` API request to Core API
     *
     * @param string get subscription request (more params detail refer to: https://api-docs.midtrans.com/#get-subscription)
     * @return mixed
     * @throws Exception
     */
    public static function getSubscription($subscriptionId)
    {
        return CoreApi::getSubscription($subscriptionId);
    }

    /**
     * Do disable `/v1/subscription/<subscription_id>/disable` API request to Core API
     *
     * @param string disable subscription request (more params detail refer to: https://api-docs.midtrans.com/#disable-subscription)
     * @return mixed
     * @throws Exception
     */
    public static function disableSubscription($subscriptionId)
    {
        return CoreApi::disableSubscription($subscriptionId);
    }

    /**
     * Do enable `/v1/subscription/<subscription_id>/enable` API request to Core API
     *
     * @param string enable subscription request (more params detail refer to: https://api-docs.midtrans.com/#enable-subscription)
     * @return mixed
     * @throws Exception
     */
    public static function enableSubscription($subscriptionId)
    {
        return CoreApi::enableSubscription($subscriptionId);
    }

    /**
     * Do update subscription `/v1/subscription/<subscription_id>` API request to Core API
     *
     * @param string update subscription request (more params detail refer to: https://api-docs.midtrans.com/#update-subscription)
     * @return mixed
     * @throws Exception
     */
    public static function updateSubscription($subscriptionId, $params)
    {
        return CoreApi::updateSubscription($subscriptionId, $params);
    }

    /**
     * Retrieve transaction status
     *
     * @param string $id Order ID or transaction ID
     *
     * @return mixed[]
     * @throws Exception
     */
    public static function status($id)
    {
        return Transaction::status($id);
    }

    /**
     * Retrieve B2B transaction status
     *
     * @param string $id Order ID or transaction ID
     *
     * @return mixed[]
     * @throws Exception
     */
    public static function statusB2b($id)
    {
        return ApiRequestor::get(
            Config::getBaseUrl() . '/v2/' . $id . '/status/b2b',
            Config::$serverKey,
            false
        );
    }

    /**
     * Approve challenge transaction
     *
     * @param string $id Order ID or transaction ID
     *
     * @return string
     * @throws Exception
     */
    public static function approve($id)
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
    public static function cancel($id)
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
    public static function expire($id)
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
    public static function refund($id, $params)
    {
        return Transaction::refund($id, $params);
    }

    /**
     * Transaction status can be updated into refund
     * if the customer decides to cancel completed/settlement payment.
     * The same refund id cannot be reused again.
     *
     * @param string $id Order ID or transaction ID
     *
     * @return mixed[]
     * @throws Exception
     */
    public static function refundDirect($id, $params)
    {
        return Transaction::refund($id, $params);
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
    public static function deny($id)
    {
        return Transaction::deny($id);
    }
}
