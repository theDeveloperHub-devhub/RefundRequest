<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model\Attribute\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use DevHub\RefundRequest\Model\ResourceModel\Status as devhubRefundStatus;

class Status implements OptionSourceInterface
{
    /**
     * Remind Status values
     */
    const PENDING = 0;
    const ACCEPT = 1;
    const REJECT = 2;
    const NA = null;
    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PENDING,  'label' => __('Pending')],
            ['value' => self::ACCEPT,  'label' => __('Accept')],
            ['value' => self::REJECT,  'label' => __('Reject')],
            ['value' => self::NA,  'label' => __('N/A')]
        ];
    }
}
