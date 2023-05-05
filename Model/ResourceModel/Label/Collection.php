<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model\ResourceModel\Label;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            \DevHub\RefundRequest\Model\Label::class,
            \DevHub\RefundRequest\Model\ResourceModel\Label::class
        );
    }
}
