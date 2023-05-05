<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Status for update refund status
 */
class Status extends AbstractDb
{
    /**
     * @var DateTime
     */
    private $datetime;

    /**
     * Status constructor.
     * @param DateTime $datetime
     * @param Context $context
     */
    public function __construct(
        DateTime $datetime,
        Context $context
    ) {
        $this->datetime = $datetime;
        parent::__construct($context);
    }
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('devhub_refundrequest', 'increment_id');
    }

    /**
     * Update status and time in bss_refundrequest table
     * @param array $incrementIds
     * @param int $refundStatus
     */
    public function updateStatusAndTime($incrementIds, $refundStatus)
    {
        $timeUpdate = $this->datetime->gmtDate('Y-m-d H:i:s');
        $connection = $this->getConnection();
        $where =  ['increment_id IN (?)' => $incrementIds];
        try {
            $connection->beginTransaction();
            $connection->update(
                $this->getMainTable(),
                ['updated_at' => $timeUpdate,'refund_status' => $refundStatus],
                $where
            );
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }

    /**
     * Update refund status in sales_order_grid table
     *
     * @param array $incrementIds
     * @param int $status
     */
    public function updateOrderRefundStatus($incrementIds, $status)
    {
        $sales_order_grid = $this->getTable('sales_order_grid');
        $connection = $this->getConnection();
        $where =  ['increment_id IN (?)' => $incrementIds];
        try {
            $connection->beginTransaction();
            $connection->update(
                $sales_order_grid,
                ['refund_status' => $status],
                $where
            );
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }
}
