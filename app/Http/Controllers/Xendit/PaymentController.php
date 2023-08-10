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
}