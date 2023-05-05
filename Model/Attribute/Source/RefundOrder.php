<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model\Attribute\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;
use DevHub\RefundRequest\Model\ResourceModel\Status;

class RefundOrder implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $orderStatusCollection;

    /**
     * @var Status
     */
    protected $devhubRefundStatus;

    /**
     * RefundOrder constructor.
     * @param CollectionFactory $orderStatusCollection
     * @param Status $devhubRefundStatus
     */
    public function __construct(
        CollectionFactory $orderStatusCollection,
        Status $devhubRefundStatus
    ) {
        $this->orderStatusCollection = $orderStatusCollection;
        $this->devhubRefundStatus = $devhubRefundStatus;
    }

    /**
     * @return Collection
     */
    public function getStatus()
    {
        return $this->orderStatusCollection->create();
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $array = [];
        foreach ($this->getStatus() as $value) {
            $array[] = ['value' => $value->getStatus(), 'label' => $value->getLabel()];
        }
        return $array;
    }
}
