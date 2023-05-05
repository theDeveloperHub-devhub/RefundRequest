<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Controller\Order;

use DevHub\RefundRequest\Helper\Data;
use DevHub\RefundRequest\Helper\Email;
use DevHub\RefundRequest\Model\RequestFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\App\RequestInterface;

class Index implements ActionInterface
{
    /**
     * @var Email
     */
    protected $emailSender;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var OrderInterface
     */
    protected $orderInterface;

    /**
     * @var RequestFactory
     */
    protected $requestFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param RequestInterface $request
     * @param Email $emailSender
     * @param Data $helper
     * @param OrderInterface $orderInterface
     * @param RequestFactory $requestFactory
     * @param ResultFactory $resultFactory
     * @param Validator $formKeyValidator
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface $request,
        Email $emailSender,
        Data $helper,
        OrderInterface $orderInterface,
        RequestFactory $requestFactory,
        ResultFactory $resultFactory,
        Validator $formKeyValidator,
        MessageManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->emailSender        = $emailSender;
        $this->helper             = $helper;
        $this->orderInterface     = $orderInterface;
        $this->requestFactory    = $requestFactory;
        $this->resultFactory = $resultFactory;
        $this->formKeyValidator = $formKeyValidator;
        $this->messageManager = $messageManager;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage("Invalid request!");
            return $resultRedirect->setPath('customer/account/');
        }
        $model          = $this->requestFactory->create();
        $data           = $this->request->getPostValue();
        if ($data) {
            if ($this->helper->getConfigEnableDropdown()) {
                $option = $data['devhub-option'];
            } else {
                $option = '';
            }
            if ($this->helper->getConfigEnableOption()) {
                $radio = $data['devhub-radio'];
            } else {
                $radio = '';
            }
            $reasonComment = $data['devhub-refund-reason'];
            $incrementId   = $data['devhub-refund-order-id'];
            $orderData     = $this->orderInterface->loadByIncrementId($incrementId);
            try {
                $model->setOption($option);
                $model->setRadio($radio);
                $model->setOrderId($incrementId);
                $model->setReasonComment($reasonComment);
                $model->setCustomerName($orderData->getCustomerName());
                $model->setCustomerEmail($orderData->getCustomerEmail());
                $model->save();
                try {
                    $this->sendEmail($orderData);
                    $this->messageManager
                        ->addSuccessMessage(__('Your refund request number #' . $incrementId . ' has been submited.'));
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    return $resultRedirect->setPath('customer/account/');
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('customer/account/');
            }
        }
        return $resultRedirect->setPath('customer/account/');
    }

    /**
     * @param $orderData
     */
    protected function sendEmail($orderData)
    {
        $emailTemplate = $this->helper->getEmailTemplate();
        $adminEmail    = $this->helper->getAdminEmail();
        $adminEmails   = explode(",", $adminEmail);
        $countEmail    = count($adminEmails);
        if ($countEmail > 1) {
            foreach ($adminEmails as $value) {
                $value             = str_replace(' ', '', $value ?? '');
                $emailTemplateData = [
                    'adminEmail'   => $value,
                    'incrementId'  => $orderData->getIncrementId(),
                    'customerName' => $orderData->getCustomerName(),
                    'createdAt'    => $orderData->getCreatedAt(),
                ];
                $this->emailSender->sendEmail($value, $emailTemplate, $emailTemplateData);
            }
        } else {
            $emailTemplateData = [
                'adminEmail'   => $adminEmail,
                'incrementId'  => $orderData->getIncrementId(),
                'customerName' => $orderData->getCustomerName(),
                'createdAt'    => $orderData->getCreatedAt(),
            ];
            $this->emailSender->sendEmail($adminEmail, $emailTemplate, $emailTemplateData);
        }
    }
}
