<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Controller\Adminhtml\Label;

use DevHub\RefundRequest\Helper\Data;
use DevHub\RefundRequest\Model\ResourceModel\Request\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassEnable extends Action
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * Mass Action Filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * MassEnable constructor.
     * @param Data $helper
     * @param Filter $filter
     * @param \DevHub\RefundRequest\Model\ResourceModel\Label\CollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        Data $helper,
        Filter $filter,
        \DevHub\RefundRequest\Model\ResourceModel\Label\CollectionFactory $collectionFactory,
        Context $context
    ) {
        $this->helper = $helper;
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($this->helper->getConfigEnableModule()):
            $setStatus = 0;
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        try {
            foreach ($collection as $item) {
                if ($item["status"] !== 0) {
                    $this->setStatus($item);
                }
                $setStatus++;
            }
            $this->messageManager->addSuccessMessage(__('%1 option(s) enabled.', $setStatus));
            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/');
        } else:
            $this->messageManager->addWarningMessage(__('Module is disabled.'));
        return $resultRedirect->setPath('*/*/');
        endif;
    }

    /**
     * @param $item
     */
    protected function setStatus($item)
    {
        $item->setData('status', 0);
        $item->save();
    }

    /**
     * Check Rule
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed("DevHub_RefundRequest::refundrequest_access_controller_label_massenable");
    }
}
