<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Controller\Adminhtml\Request;

use DevHub\RefundRequest\Helper\Data;
use DevHub\RefundRequest\Helper\Email;
use DevHub\RefundRequest\Model\ResourceModel\Request\CollectionFactory;
use DevHub\RefundRequest\Model\ResourceModel\Status;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Locale\ListsInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Ui\Component\MassAction\Filter;

class MassAccept extends Action
{
    const STATUS_ACCEPT = 1;
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Email
     */
    protected $emailSender;

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
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @var ListsInterface
     */
    protected $localeLists;

    /**
     * @var Status
     */
    protected $statusResourceModel;

    /**
     * MassAccept constructor.
     * @param Email $emailSender
     * @param Data $helper
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param TimezoneInterface $timezone
     * @param ListsInterface $localeLists
     * @param Context $context
     * @param Status $statusResourceModel
     */
    public function __construct(
        Email $emailSender,
        Data $helper,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        TimezoneInterface $timezone,
        ListsInterface $localeLists,
        Context $context,
        Status $statusResourceModel
    ) {
        $this->helper = $helper;
        $this->emailSender = $emailSender;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->timezone = $timezone;
        $this->localeLists = $localeLists;
        $this->statusResourceModel = $statusResourceModel;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($this->helper->getConfigEnableModule()) {
            $acceptOrder = 0;
            $incrementIds = [];
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            try {
                foreach ($collection as $key => $item) {
                    if ($item["refund_status"] != 1) {
                        $this->sendEmail($item);
                        $incrementIds[$key] = $item["increment_id"];
                        $acceptOrder++;
                    }
                }
                $this->statusResourceModel->updateOrderRefundStatus($incrementIds, self::STATUS_ACCEPT);
                $this->statusResourceModel->updateStatusAndTime($incrementIds, self::STATUS_ACCEPT);
                $this->messageManager->addSuccessMessage(__('%1 request has been accepted', $acceptOrder));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $this->messageManager->addWarningMessage(__('Module is disabled.'));
        }
        return $resultRedirect->setPath('*/*/');
    }
    /**
     * @param $item
     */
    protected function sendEmail($item)
    {
        $customerEmail = $item->getCustomerEmail();
        $timezone = $this->scopeConfig->getValue('general/locale/timezone', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $date = $this->timezone->date();
        $timezoneLabel = $this->getTimezoneLabelByValue($timezone);
        $date = $date->format('Y-m-d h:i:s A') . " " . $timezoneLabel;
        $emailTemplate = $this->helper->getAcceptEmailTemplate();
        $emailTemplateData = [
            'incrementId' => $item["increment_id"],
            'id' => $item["id"],
            'timeApproved'=> $date,
            'customerName' => $item["customer_name"]
        ];
        $this->emailSender->sendEmail($customerEmail, $emailTemplate, $emailTemplateData);
    }

    /**
     * @param $timezoneValue
     * @return string
     */
    protected function getTimezoneLabelByValue($timezoneValue)
    {
        $timezones = $this->localeLists->getOptionTimezones();
        $label = '';
        foreach ($timezones as $zone) {
            if ($zone["value"] == $timezoneValue) {
                $label = $zone["label"];
            }
        }
        return $label;
    }

    /**
     * Check Rule
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed("DevHub_RefundRequest::refundrequest_access_controller_request_massaccept");
    }
}
