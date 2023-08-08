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


class PaymentController extends Controller
{
    public function payment($id)
    {
        $order = Order::find($id);
        $user = User::find($order->user_id);
        $product = Product::find($order->product_id);
        $payment = $order->toArray();
        // print_r($order);
        // print_r($user);
        // print_r($product);
        try {
            DB::beginTransaction();
            Payment::updateOrCreate([
                'order_id' => $order->id,
            ],
            [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            DB::commit();

            Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));
            $fees = 5000;
            $params = [
                'external_id' => '$order->id2',
                'amount' => $product->price+$fees,
                // 'description' => '',
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $user->name,
                    // 'surname' => '',
                    'email' => $user->email,
                    'mobile_number' => '123',
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
                'success_redirect_url' => route('success'),
                'failure_redirect_url' => route('failure'),
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => $product->name,
                        'quantity' => 1,
                        'price' => $product->price,
                        // 'category' => '',
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
            print_r($params);
            $createInvoice = \Xendit\Invoice::create($params);
            return Redirect::to($createInvoice['invoice_url']);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            // DB::rollBack();
            // return redirect()->back()->with('status', $ex->getMessage());
        }
    }
}