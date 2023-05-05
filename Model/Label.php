<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model;

use Magento\Framework\Model\AbstractModel;

class Label extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Label::class);
    }
}
