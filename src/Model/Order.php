<?php

namespace Iidev\AvaTaxStatuses\Model;

use XCart\Extender\Mapping\Extender;
use Doctrine\ORM\Mapping as ORM;
use \Iidev\AvaTaxStatuses\Core\TaxCore;
use Iidev\AvaTaxStatuses\Model\AvaTaxStatuses;
use XLite\Core\Database;

/**
 *
 * @Extender\Mixin
 */
abstract class Order extends \XLite\Model\Order
{
    /**
     * AvaTax import flag
     *
     * @var boolean
     *
     * @ORM\Column (type="boolean")
     */
    protected $avaTaxImported = false;

    /**
     * Set avaTax import flag
     */
    public function setAvaTaxImported($avaTaxImported)
    {
        $this->avaTaxImported = $avaTaxImported;

        return $this;
    }

    /**
     * Get avaTax import flag
     */
    public function getAvaTaxImported()
    {
        return $this->avaTaxImported;
    }

    public function processSucceed()
    {
        parent::processSucceed();

        if ($this->getAvaTaxErrorsFlag())
            return;

        if (!$this->isAvataxTransactionsApplicable())
            return;

        TaxCore::getInstance()->setFinalCalculationFlag(true);

        $response = TaxCore::getInstance()->getStateTax($this, true);

        if ($response[0] === false) {
            $this->setAvaTaxImported(true);
        }
    }

    public function processStatus($status, $type)
    {
        parent::processStatus($status, $type);

        if (!$this->getPaymentStatus())
            return;

        $oldPaymentStatus = $this->getOldPaymentStatusCode();

        $order = Database::getRepo('XLite\Model\Order')->find($this->getOrderId());

        if (!$order instanceof \XLite\Model\Order)
            return;

        if (!$oldPaymentStatus)
            return;

        if (!$this->getAvaTaxStatusesByOrderStatuses())
            return;

        if (!$this->isAvataxTransactionsApplicable())
            return;

        if ($this->getAvaTaxImported())
            return;

        $response = TaxCore::getInstance()->getStateTax($order, true);

        if ($response[0] === false) {
            $this->setAvaTaxImported(true);
        }
    }

    private function getAvaTaxStatusesByOrderStatuses(): ?AvaTaxStatuses
    {
        if (!$this->getPaymentStatus() || !$this->getShippingStatus())
            return null;

        /** @var AvaTaxStatuses $result */
        $result = Database::getRepo(AvaTaxStatuses::class)->findOneBy([
            'paymentStatus' => $this->getPaymentStatus()->getId(),
            'shippingStatus' => $this->getShippingStatus()->getId(),
        ]);

        return $result;
    }
}
