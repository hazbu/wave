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

// Tripay
use ZerosDev\TriPay\Client as TriPayClient;
use ZerosDev\TriPay\Support\Constant;
use ZerosDev\TriPay\Support\Helper;
use ZerosDev\TriPay\Transaction;


class PaymentController extends Controller
{
    public function payment2($id)
    {
        $order = Order::find($id);
        $user = User::find($order->user_id);
        $product = Product::find($order->product_id);
        $payment = Payment::where('order_id', $order->order_id)->first();
        
        try {
            Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));
            $fees = 5000;
            $params = [
                'external_id' => $order->order_id,
                'amount' => $product->price+$fees,
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
                        'type' => 'Layanan',
                        'value' => $fees
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
                    'amount' => $product->price+$fees,
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
        $order = Order::find($id);
        $user = User::find($order->user_id);
        $product = Product::find($order->product_id);
        $payment = Payment::where('order_id', $order->order_id)->first();
        
        $merchantCode = 'T24618';
        $apiKey = 'b6hJYQJCY2dTlKNKvSO3Hl9vMYvEnvuD99wz6USu';
        $privateKey = 'vH7U4-GKjN5-VZ3Eu-hYhXA-VvTWS';
        $mode = Constant::MODE_DEVELOPMENT;
        $guzzleOptions = []; // Your additional Guzzle options (https://docs.guzzlephp.org/en/stable/request-options.html)

        $client = new TriPayClient($merchantCode, $apiKey, $privateKey, $mode, $guzzleOptions);
        $transaction = new Transaction($client);

        /**
         * `amount` will be calculated automatically from order items
         * so you don't have to enter it
         * In this example, amount will be 40.000
         */
        $result = $transaction
            ->addOrderItem($product->name, $product->price, 1)
            ->create([
                'method' => 'BRIVA',
                'merchant_ref' => $order->order_id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '081234567890',
                'expired_time' => Helper::makeTimestamp('6 HOUR'), // see Supported Time Units
            ]);
        
        echo $result->getBody()->getContents();
        
        /**
        * For debugging purpose
        */
        $debugs = $client->debugs();
        echo json_encode($debugs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}