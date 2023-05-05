<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Block\Adminhtml\Label\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * Tabs constructor.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('label_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('EDIT OPTION'));
    }
}
