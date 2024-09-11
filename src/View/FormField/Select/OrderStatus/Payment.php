<?php

namespace Iidev\AvaTaxStatuses\View\FormField\Select\OrderStatus;

class Payment extends \XLite\View\FormField\Select\OrderStatus\Payment
{
    protected function getOptions()
    {
        $list = parent::getOptions();

        unset($list[0]);

        return $list;
    }
}
