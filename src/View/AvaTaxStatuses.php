<?php

namespace Iidev\AvaTaxStatuses\View;

use XCart\Extender\Mapping\ListChild;

/**
 * @ListChild (list="admin.center", zone="admin")
 */
class AvaTaxStatuses extends \XLite\View\AView
{
    /**
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        return array_merge(parent::getAllowedTargets(), ['ava_tax_statuses']);
    }

    /**
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/Iidev/AvaTaxStatuses/admin/statuses.twig';
    }
}
