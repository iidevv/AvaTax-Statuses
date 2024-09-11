<?php

namespace Iidev\AvaTaxStatuses;

use XLite\Core\Converter;
use XLite\Module\AModule;
abstract class Main extends AModule
{
    public static function getSettingsForm()
    {
        return Converter::buildURL('ava_tax_statuses');
    }
}
