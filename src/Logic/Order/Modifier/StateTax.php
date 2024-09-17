<?php

namespace Iidev\AvaTaxStatuses\Logic\Order\Modifier;

use Iidev\AvaTaxStatuses\Core\TaxCore;
use XCart\Extender\Mapping\Extender;

/**
 * @Extender\Mixin
 */
class StateTax extends \XC\AvaTax\Logic\Order\Modifier\StateTax
{
    /**
     * Calculate
     *
     * @return \XLite\Model\Order\Surcharge[]
     */
    public function calculate()
    {
        $orderState = $this->order->getEventFingerprint();
        unset(
            $orderState['avaTaxErrorsFlag'],
            $orderState['paymentMethodId'],
            $orderState['shippingMethodsHash'],
            $orderState['paymentMethodsHash'],
            $orderState['shippingAddressFields'],
            $orderState['billingAddressFields']
        );
        if ($this->order->getProfile()) {
            if ($this->order->getProfile()->getShippingAddress()) {
                $orderState['shippingAddress'] = $this->order->getProfile()->getShippingAddress()->getFieldsHash();
            }
            if ($this->order->getProfile()->getBillingAddress()) {
                $orderState['billingAddress'] = $this->order->getProfile()->getBillingAddress()->getFieldsHash();
            }
        }

        $hash = md5(serialize($orderState));
        $cacheKey = 'avatax_' . $hash;

        $surcharges = [];

        $cacheDriver = \XLite\Core\Database::getCacheDriver();

        $error = null;
        $taxes = null;

        $cached = $cacheDriver->fetch($cacheKey);

        if ($cached && !TaxCore::getInstance()->shouldRecordTransaction()) {
            $error = null;
            $taxes = $cached;
        } elseif ($this->canBeCalculatedNow()) {
            [$error, $taxes] = TaxCore::getInstance()->getStateTax($this->order, false, true);
            if (!$error) {
                $cacheDriver->save($cacheKey, $taxes, static::AVATAX_CACHE_TTL);
            }
        }

        if ($taxes) {
            if (\XLite\Core\Config::getInstance()->XC->AvaTax->display_as_summary) {
                $taxCost = array_reduce($taxes, static function ($cost, $tax) {
                    return $cost + floatval($tax['cost']);
                }, 0);

                $name = $this->code . '.summary';
                $surcharge = $this->addOrderSurcharge($name, $taxCost);
                $surcharge->setName(static::t('Taxes'));

                $surcharges[] = $surcharge;
            } else {
                foreach ($taxes as $tax) {
                    $name = $this->code . '.' . $tax['code'];
                    $surcharge = $this->addOrderSurcharge($name, $tax['cost']);
                    $surcharge->setName($tax['name']);

                    $surcharges[] = $surcharge;
                }
            }
        }

        $this->order->setAvaTaxErrorsFlag((bool) $error);

        $sessionId = \XLite\Core\Session::getInstance()->getID();
        $cacheDriver->save('avatax_last_errors' . $sessionId, $error, 0);
        $cacheDriver->save('avatax_last_errors_ajax' . $sessionId, $error, 0);

        if (is_array($error) && !(\XLite::getController() instanceof \XLite\Controller\Customer\Checkout)) {
            foreach ($error as $e) {
                \XLite\Core\TopMessage::addError($e);
            }
        }

        return $surcharges;
    }
}
