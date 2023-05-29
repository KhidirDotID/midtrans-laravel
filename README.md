# midtrans-laravel
A Midtrans Wrapper for Laravel

## Installation

1. Install the package
    ```bash
    composer require khidirdotid/midtrans-laravel
    ```
2. Publish the config file
    ```bash
    php artisan vendor:publish --provider="KhidirDotID\Midtrans\Providers\MidtransServiceProvider"
    ```
3. Add the Facade to your `config/app.php` into `aliases` section
    ```php
    'Midtrans' => KhidirDotID\Midtrans\Facades\Midtrans::class,
    ```
4. Add ENV data
    ```env
    MIDTRANS_PRODUCTION_SERVER_KEY=Mid-server-
    MIDTRANS_PRODUCTION_CLIENT_KEY=Mid-client-
    MIDTRANS_SANDBOX_SERVER_KEY=SB-Mid-server-
    MIDTRANS_SANDBOX_CLIENT_KEY=SB-Mid-client-
    MIDTRANS_ENVIRONMENT=sandbox
    MIDTRANS_3DS=false
    MIDTRANS_APPEND_NOTIF_URL=
    MIDTRANS_OVERRIDE_NOTIF_URL=
    ```

    or you can set it through the controller
    ```php
    \Midtrans::setServerKey($serverKey);
    \Midtrans::setClientKey($clientKey);
    \Midtrans::setProduction(true);
    \Midtrans::set3ds(true);
    \Midtrans::setAppendNotifUrl(route('midtrans.ipn'));
    \Midtrans::setOverrideNotifUrl(route('midtrans.ipn'));
    ```

## Usage

### Snap

1. Get Snap Token
    ```php
    $params = [
        'transaction_details' => [
            'order_id' => rand(),
            'gross_amount' => 10000
        ]
    ];

    $snapToken = \Midtrans::getSnapToken($params);
    ```
2. Initialize Snap JS when customer click pay button
    ```html
    <button id="pay-button">Pay!</button>
    <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre>

    <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_SANDBOX_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken acquired from previous step
            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onPending: function(result) {
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                },
                // Optional
                onError: function(result) {
                    /* You may add your own js here, this is just example */
                    document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                }
            });
        };
    </script>
    ```

### Snap Redirect

1. Get Redirection URL of a Payment Page
    ```php
    $params = [
        'transaction_details' => [
            'order_id' => rand(),
            'gross_amount' => 10000
        ]
    ];

    try {
        // Get Snap Payment Page URL
        $paymentUrl = \Midtrans::createTransaction($params)->redirect_url;

        // Redirect to Snap Payment Page
        return redirect()->away($paymentUrl);
    } catch (\Throwable $th) {
        throw $th;
    }
    ```

### Core API

1. Create Transaction details
    ```php
    $transaction_details = [
        'order_id' => time(),
        'gross_amount' => 200000
    ];
    ```
2. Create Item Details, Billing Address, Shipping Address, and Customer Details (Optional)
    ```php
    // Populate items
    $items = [
        [
            'id' => 'item1',
            'price' => 100000,
            'quantity' => 1,
            'name' => 'Adidas f50'
        ],
        [
            'id'       => 'item2',
            'price'    => 50000,
            'quantity' => 2,
            'name'     => 'Nike N90'
        ]
    ];

    // Populate customer's billing address
    $billing_address = [
        'first_name' => "Andri",
        'last_name' => "Setiawan",
        'address' => "Karet Belakang 15A, Setiabudi.",
        'city' => "Jakarta",
        'postal_code' => "51161",
        'phone' => "081322311801",
        'country_code' => 'IDN'
    ];

    // Populate customer's shipping address
    $shipping_address = [
        'first_name' => "John",
        'last_name' => "Watson",
        'address' => "Bakerstreet 221B.",
        'city' => "Jakarta",
        'postal_code' => "51162",
        'phone' => "081322311801",
        'country_code' => 'IDN'
    ];

    // Populate customer's info
    $customer_details = [
        'first_name' => "Andri",
        'last_name' => "Setiawan",
        'email' => "test@test.com",
        'phone' => "081322311801",
        'billing_address' => $billing_address,
        'shipping_address' => $shipping_address
    ];
    ```
4. Create Transaction Data
    ```php
    // Transaction data to be sent
    $transaction_data = [
        'payment_type' => 'bank_transfer',
        'bank_transfer' => [
            'bank' => 'bca'
        ],
        'transaction_details' => $transaction_details,
        'item_details' => $items,
        'customer_details' => $customer_details
    ];
    ```
6. Charge
    ```php
    $response = \Midtrans::charge($transaction_data);
    ```

### Handle HTTP Notification

1. Create route to handle notifications
    ```php
    Route::match(['GET', 'POST'], 'midtrans.ipn', [PaymentController::class, 'midtransIpn'])->name('midtrans.ipn');
    ```
2. Create method in controller
    ```php
    public function midtransIpn(Request $request)
    {
        try {
            $response = \Midtrans::status($request->transaction_id);
            
            if (in_array($response->transaction_status, ['settlement', 'capture']) && $response->fraud_status === 'accept') {
                // TODO: Set payment status in merchant's database to 'success'
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    ```
3. Except verify CSRF token in `app/Http/Middleware/VerifyCsrfToken.php`
    ```php
    protected $except = [
        'midtrans/ipn'
    ];
    ```
