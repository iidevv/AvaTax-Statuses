<?php

namespace Iidev\AvaTaxStatuses\View\Admin;


class AvaTaxStatuses extends \XLite\View\AView
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'avatax_statuses';

        return $result;
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/Iidev/AvaTaxStatuses/admin/statuses.twig';
    }
}
