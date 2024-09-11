<?php

namespace Iidev\AvaTaxStatuses\View\FormField\Inline\Select\OrderStatus;

use Iidev\AvaTaxStatuses\View\FormField\Select\OrderStatus\Payment as PaymentInline;

class Payment extends \XLite\View\FormField\Inline\Select\OrderStatus\Payment
{
    /**
     * Define form field
     *
     * @return string
     */
    protected function defineFieldClass()
    {
        return PaymentInline::class;
    }
}
