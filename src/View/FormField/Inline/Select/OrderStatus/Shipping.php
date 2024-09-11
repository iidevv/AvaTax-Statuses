<?php

namespace Iidev\AvaTaxStatuses\View\FormField\Inline\Select\OrderStatus;

use Iidev\AvaTaxStatuses\View\FormField\Select\OrderStatus\Shipping as ShippingInline;

class Shipping extends \XLite\View\FormField\Inline\Select\OrderStatus\Shipping
{
    /**
     * Define form field
     *
     * @return string
     */
    protected function defineFieldClass()
    {
        return ShippingInline::class;
    }
}
