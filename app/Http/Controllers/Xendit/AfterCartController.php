<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AfterCartController extends Controller
{
    public function success()
    {
        return route('voyager.orders.index');
    }

    public function failure()
    {
        return route('voyager.orders.index');
    }
}