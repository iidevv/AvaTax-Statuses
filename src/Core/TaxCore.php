<?php

namespace Iidev\AvaTaxStatuses\Core;

use XCart\Extender\Mapping\Extender;

/**
 * @Extender\Mixin
 */
class TaxCore extends \XC\AvaTax\Core\TaxCore
{
    /**
     * Get state tax
     *
     * @param \XLite\Model\Order $order Order
     *
     * @return array
     */
    public function getStateTax(\XLite\Model\Order $order, $isCommitted = false)
    {
        $result = [false, []];

        $messages = [];
        $data = $this->getInformation($order, $messages);

        if ($data) {
            if ($isCommitted) {
                $data['commit'] = true;
                $data['type'] = 'SalesInvoice';
            }
            return $this->createTransactionRequest($data) ?: $result;
        } else {
            $result[0] = $messages;
        }

        return $result;
    }

    public function adjustTransactionRequest(\XC\AvaTax\Model\Order $order, string $reason, string $reasonDescription = '', $isCommitted = false)
    {
        $messages = [];
        $oldOrderData = $this->getInformation($order, $messages);
        $dataProvider = new \XC\AvaTax\Core\DataProvider\Order($order);
        $data = $dataProvider->getAdjustTransactionModel($oldOrderData, $reason, $reasonDescription);

        if ($isCommitted) {
            $data['newTransaction']['commit'] = true;
            $data['newTransaction']['type'] = 'SalesInvoice';
        }

        $this->avataxRequest(
            "companies/{$dataProvider->companyCodeEncoded()}/transactions/{$dataProvider->transactionCodeEncoded()}/adjust",
            $data
        );
    }
}
