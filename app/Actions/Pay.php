<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Http;

class Pay extends AbstractAction
{
    public function getTitle()
    {
        return 'Pay';
    }

    public function getIcon()
    {
        return 'voyager-dollar';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        return route('wave.payment', $this->data->id);
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'orders';
    }
}