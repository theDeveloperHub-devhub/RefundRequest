<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Controller\Adminhtml\Label;

use Magento\Backend\Model\Session;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\App\ActionInterface;
use DevHub\RefundRequest\Model\LabelFactory;
use Magento\Framework\App\RequestInterface;

class Save implements ActionInterface
{
    /**
     * @var Session
     */
    protected $backendSession;

    /**
     * @var LabelFactory
     */
    protected $labelFactory;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

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
     * @param Session $backendSession
     * @param LabelFactory $labelFactory
     * @param ResultFactory $resultFactory
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface $request,
        Session $backendSession,
        LabelFactory $labelFactory,
        ResultFactory $resultFactory,
        MessageManagerInterface $messageManager
    ) {
        $this->request = $request;
        $this->backendSession = $backendSession;
        $this->labelFactory = $labelFactory;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $model = $this->labelFactory->create();
        $data = $this->request->getPostValue();
        $model->setData($data);
        if ($data) {
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('The option has been saved.'));
                $this->backendSession->setPostData(false);
                if ($this->request->getParam('back')) {
                    $resultRedirect->setPath('*/*/');
                    return $resultRedirect;
                }
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving.'));
            }
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }
}
