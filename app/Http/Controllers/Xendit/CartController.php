<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Http\Requests\StoreCartRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Xendit\Xendit;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('theme::settings.index', [
            'title' => 'Buy the Ebook',
            'price' => 250000,
            'section' => 'payment'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function pay(StoreCartRequest $request)
    {
        try {
            Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));
            $params = [
                'external_id' => $request->order_id,
                'amount' => 125000,
                'description' => 'Pembelian Kamus Laravel 2022',
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $request->name,
                    'surname' => $request->last_name,
                    'email' => $request->email,
                    'mobile_number' => $request->phone_number,
                    'address' => [
                        [
                            'city' => $request->city,
                            'country' => $request->country,
                            'postal_code' => $request->postal_code,
                            'state' => $request->state,
                            'street_line1' => $request->street_line1,
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
                        'name' => 'Kamus Laravel 2022',
                        'quantity' => 1,
                        'price' => 125000,
                        'category' => 'Education',
                        'url' => url('/')
                    ]
                ],
                'fees' => [
                    [
                        'type' => 'ADMIN',
                        'value' => 5000
                    ]
                ]
            ];
            $createInvoice = \Xendit\Invoice::create($params);
            return Redirect::to($createInvoice['invoice_url']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('status', $ex->getMessage());
        }
    }
    
    public function store(StoreCartRequest $request)
    {
        try {
            //code...
            DB::beginTransaction();
            $order_id = 'kamus_laravel_2022_' . date('YmdHis');
            $request->merge([
                'order_id' => $order_id,
                'amount' => 125000,
                'description' => 'Pembelian Kamus Laravel 2022',
            ]);
            Payment::updateOrCreate([
                'order_id' => $json->external_id,
                'amount' => $json->amount,
            ],$request->all());
            DB::commit();

            Xendit::setApiKey(env('XENDIT_SECRET_API_KEY'));
            $params = [
                'external_id' => $order_id,
                'amount' => 125000,
                'description' => 'Pembelian Kamus Laravel 2022',
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $request->name,
                    'surname' => $request->last_name,
                    'email' => $request->email,
                    'mobile_number' => $request->phone_number,
                    'address' => [
                        [
                            'city' => $request->city,
                            'country' => $request->country,
                            'postal_code' => $request->postal_code,
                            'state' => $request->state,
                            'street_line1' => $request->street_line1,
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
                        'name' => 'Kamus Laravel 2022',
                        'quantity' => 1,
                        'price' => 125000,
                        'category' => 'Education',
                        'url' => url('/')
                    ]
                ],
                'fees' => [
                    [
                        'type' => 'ADMIN',
                        'value' => 5000
                    ]
                ]
            ];
            $createInvoice = \Xendit\Invoice::create($params);
            return Redirect::to($createInvoice['invoice_url']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('status', $ex->getMessage());
        }
    }
}