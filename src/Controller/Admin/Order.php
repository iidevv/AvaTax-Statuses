<?php

namespace Iidev\AvaTaxStatuses\Controller\Admin;

use XCart\Extender\Mapping\Extender;
use XC\AvaTax\Core\TaxCore;

/**
 * @Extender\Mixin
 */
class Order extends \XC\AvaTax\Controller\Admin\Order
{
    /**
     * @param array                                     $diff
     * @param \XLite\Model\Order|\XC\AvaTax\Model\Order $order
     */
    protected function orderUpdatedCallback(array $diff, \XLite\Model\Order $order)
    {
        $isCommited = $order->getAvaTaxImported();

        if ($diff && TaxCore::getInstance()->isValid() && $order->hasAvataxTaxes()) {
            \Iidev\AvaTaxStatuses\Core\TaxCore::getInstance()->adjustTransactionRequest($order, TaxCore::OTHER, print_r($diff, true), $isCommited);
        }
    }
}
