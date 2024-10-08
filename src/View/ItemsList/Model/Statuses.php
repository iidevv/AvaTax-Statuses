<?php

namespace Iidev\AvaTaxStatuses\View\ItemsList\Model;

use Iidev\AvaTaxStatuses\Model\AvaTaxStatuses;
use Iidev\AvaTaxStatuses\View\FormField\Inline\Select\OrderStatus\Shipping;
use Iidev\AvaTaxStatuses\View\FormField\Inline\Select\OrderStatus\Payment;

class Statuses extends \XLite\View\ItemsList\Model\Table
{
    public static function getAllowedTargets()
    {
        $list = parent::getAllowedTargets();

        $list[] = 'ava_tax_statuses';

        return $list;
    }

    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/Iidev/AvaTaxStatuses/items_list/style.less';

        return $list;
    }

    protected function wrapWithFormByDefault()
    {
        return true;
    }

    protected function getFormTarget()
    {
        return 'ava_tax_statuses';
    }

    protected function getListNameSuffixes()
    {
        return ['ava_tax_statuses'];
    }

    protected function getRemoveMessage($count)
    {
        return static::t('AvataxStatuses x items has been removed', ['count' => $count]);
    }

    protected function getCreateMessage($count)
    {
        return static::t('AvataxStatuses x items has been created', ['count' => $count]);
    }

    protected function checkACL()
    {
        return parent::checkACL()
            && \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage catalog');
    }

    protected function getFormOptions()
    {
        return array_merge(parent::getFormOptions(), [
            \XLite\View\Form\AForm::PARAM_CONFIRM_REMOVE => true,
        ]);
    }

    protected function isInlineCreation()
    {
        return static::CREATE_INLINE_TOP;
    }

    protected function getSearchPanelClass()
    {
        return '';
    }

    protected function isCreation()
    {
        return static::CREATE_INLINE_TOP;
    }

    protected function isExportable()
    {
        return false;
    }

    protected function isRemoved()
    {
        return true;
    }

    protected function isSwitchable()
    {
        return true;
    }

    protected function isSelectable()
    {
        return false;
    }

    protected function defineRepositoryName(): string
    {
        return AvaTaxStatuses::class;
    }

    protected function getBlankItemsListDescription()
    {
        return static::t('Table is empty');
    }

    protected function getPanelClass()
    {
        return \XLite\View\StickyPanel\ItemsListForm::class;
    }

    protected function getContainerClass()
    {
        return parent::getContainerClass() . ' avatax-statuses';
    }

    protected function getCreateButtonLabel()
    {
        return static::t('Add condition');
    }

    /**
     * @inheritDoc
     */
    protected function defineColumns()
    {
        return [
            'paymentStatus' => [
                static::COLUMN_NAME => static::t('Payment status'),
                static::COLUMN_CLASS => Payment::class,
                static::COLUMN_ORDERBY => 100,
            ],
            'shippingStatus' => [
                static::COLUMN_NAME => static::t('Shipping status'),
                static::COLUMN_CLASS => Shipping::class,
                static::COLUMN_ORDERBY => 200,
            ],
        ];
    }
}