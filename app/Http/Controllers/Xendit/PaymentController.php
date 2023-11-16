<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use App\Order;
use App\Models\User;
use App\Models\Payment;
use App\Product;
use Xendit\Xendit;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Tripay
use ZerosDev\TriPay\Client as TriPayClient;
use ZerosDev\TriPay\Support\Constant;
use ZerosDev\TriPay\Support\Helper;
use ZerosDev\TriPay\Transaction;
use ZerosDev\TriPay\Client;
use ZerosDev\TriPay\Merchant;


class PaymentController extends Controller
{
    public function xendit($id)
    {
        $order = Order::find($id);
        $user = User::find($order->user_id);
        $product = Product::find($order->product_id);
        $payment = Payment::where('order_id', $order->order_id)->first();
        
        try {
            Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));
            $fee = $product->price * 0.05;
            $total = $product->price + $fee;
            
            $params = [
                'payment_methods' => ['OVO','DANA'],
                'external_id' => $order->order_id,
                'amount' => $total,
                'description' => ' ',
                'invoice_duration' => 86400,
                'payer_email' => $user->email,
                'customer' => [
                    'given_names' => $user->name,
                    'surname' => ' ',
                    'email' => $user->email,
                    'mobile_number' => '+62',
                    'address' => [
                        [
                            'city' => '',
                            'country' => '',
                            'postal_code' => '',
                            'state' => '',
                            'street_line1' => '',
                            'street_line2' => ''
                        ]
                    ]
                ],
                'customer_notification_preference' => [
                    'invoice_created' => [
                        'whatsapp',
                        'sms',
                        'email',
                        'viber'
                    ],
                    'invoice_reminder' => [
                        'whatsapp',
                        'sms',
                        'email',
                        'viber'
                    ],
                    'invoice_paid' => [
                        'whatsapp',
                        'sms',
                        'email',
                        'viber'
                    ],
                    'invoice_expired' => [
                        'whatsapp',
                        'sms',
                        'email',
                        'viber'
                    ]
                ],
                // 'success_redirect_url' => route('voyager.payments.index'),
                // 'failure_redirect_url' => route('voyager.payments.index'),
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => $product->name,
                        'quantity' => 1,
                        'price' => $product->price,
                        'category' => ' ',
                        'url' => url('/')
                    ]
                ],
                'fees' => [
                    [
                        'type' => 'Biaya Layanan',
                        'value' => $fee
                    ]
                ]
            ];
            if($payment){
                $createInvoice = \Xendit\Invoice::retrieve($payment->invoice_id);
            }else{
                $createInvoice = \Xendit\Invoice::create($params);
                $xenditInvoiceId = $createInvoice['id'];
                DB::beginTransaction();
                Payment::updateOrCreate([
                    'order_id' => $order->order_id,
                ],
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'invoice_id' => $xenditInvoiceId,
                    'amount' => $total,
                ]);
                DB::commit();
                }            
            return Redirect::to($createInvoice['invoice_url']);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            DB::rollBack();
            // return redirect()->back()->with('status', $ex->getMessage());
        }
    }

    public function payment($id)
    {
        return $this->xendit($id);
        // return $this->getChannel();
    }

    public function getChannel()
    {
        $mode = Constant::MODE_DEVELOPMENT;
        $merchantCode = ($mode == Constant::MODE_DEVELOPMENT) ? 'T13468' : 'T24618';
        $apiKey = ($mode == Constant::MODE_DEVELOPMENT) ? 'DEV-AbweW8CFMneMP727Zv69vvAT8WWDQekVhOYLN2Fc' : 'b6hJYQJCY2dTlKNKvSO3Hl9vMYvEnvuD99wz6USu';
        $privateKey = ($mode == Constant::MODE_DEVELOPMENT) ? '9jEr8-ncXml-aZKTc-Hl5mJ-PRidq' : 'vH7U4-GKjN5-VZ3Eu-hYhXA-VvTWS';
        $guzzleOptions = []; // Your additional Guzzle options (https://docs.guzzlephp.org/en/stable/request-options.html)

        $config = [
            'mode' => $mode,
            'merchant_code' => $merchantCode,
            'api_key' => $apiKey,
            'private_key' => $privateKey,
            'guzzle_options' => []
        ];
        $client = new Client($config);
        $merchant = new Merchant($client);
        $result = $merchant->paymentChannels();
        echo $result->getBody()->getContents();
    }

    public function tripay($id)
    {
        $order = Order::find($id);
        $user = User::find($order->user_id);
        $product = Product::find($order->product_id);
        $payment = Payment::where('order_id', $order->order_id)->first();
        
        $mode = Constant::MODE_DEVELOPMENT;
        $merchantCode = ($mode == Constant::MODE_DEVELOPMENT) ? 'T13468' : 'T24618';
        $apiKey = ($mode == Constant::MODE_DEVELOPMENT) ? 'DEV-AbweW8CFMneMP727Zv69vvAT8WWDQekVhOYLN2Fc' : 'b6hJYQJCY2dTlKNKvSO3Hl9vMYvEnvuD99wz6USu';
        $privateKey = ($mode == Constant::MODE_DEVELOPMENT) ? '9jEr8-ncXml-aZKTc-Hl5mJ-PRidq' : 'vH7U4-GKjN5-VZ3Eu-hYhXA-VvTWS';
        $guzzleOptions = []; // Your additional Guzzle options (https://docs.guzzlephp.org/en/stable/request-options.html)

        $config = [
            'mode' => $mode,
            'merchant_code' => $merchantCode,
            'api_key' => $apiKey,
            'private_key' => $privateKey,
            'guzzle_options' => []
        ];

        if($payment){
            $checkoutUrl = 'https://tripay.co.id/checkout/'.$payment->invoice_id;
        }else{
            $client = new TriPayClient($config);
            $transaction = new Transaction($client);
            $result = $transaction
                ->addOrderItem($product->name, $product->price, 1)
                ->create([
                    'method' => 'OVO',
                    'merchant_ref' => $order->order_id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => '08123456789',
                    // 'expired_time' => Helper::makeTimestamp('6 HOUR'), // see Supported Time Units
                    'expired_time' => Carbon::now('Asia/Jakarta')->addHours(6)->timestamp,
                ]);

            $jsonResponse = $result->getBody()->getContents();
            $responseData = json_decode($jsonResponse, true);
            // echo $result->getBody()->getContents();
            // die();
            $checkoutUrl = $responseData['data']['checkout_url'];

            DB::beginTransaction();
                Payment::updateOrCreate([
                    'order_id' => $order->order_id,
                ],
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'invoice_id' => $responseData['data']['reference'],
                    'amount' => $product->price,
                ]);
                DB::commit();
        }
        return Redirect::to($checkoutUrl);
        // $debugs = $client->debugs();
        // echo json_encode($debugs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}