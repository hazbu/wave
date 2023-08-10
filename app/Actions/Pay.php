<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

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
            'class' => 'btn btn-sm btn-success pull-right open-modal-button data-toggle="modal" data-target="#custom-modal" data-id="'.$this->data->id.'"',
            'style' => 'margin-right: 6px;',
        ];
    }

    public function getDefaultRoute()
    {
        // return route('wave.payment', $this->data->id);
        return '#';
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'orders';
    }
}