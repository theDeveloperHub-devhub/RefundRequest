<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Request extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('devhub_refundrequest', 'id');
    }
}
