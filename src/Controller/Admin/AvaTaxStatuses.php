<?php

namespace Iidev\AvaTaxStatuses\Controller\Admin;

use XLite\Core\Converter;

class AvaTaxStatuses extends \XLite\Controller\Admin\AAdmin
{
    public function getTitle()
    {
        return static::t('AvaTax Statuses');
    }

    public static function getAvaTaxSetupUrl()
    {
        return Converter::buildURL('module', '', ['moduleId' => 'XC-AvaTax']);
    }

    public function isRecordTransactions()
    {
        return \XLite\Core\Config::getInstance()->XC->AvaTax->record_transactions;
    }
}

