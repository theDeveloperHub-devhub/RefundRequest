<?php

declare(strict_types=1);

namespace  DevHub\RefundRequest\Plugin;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;

class Validate
{
    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @param RedirectFactory $resultRedirectFactory
     * @param MessageManagerInterface $messageManager
     */
    public function __construct(
        RedirectFactory $resultRedirectFactory,
        MessageManagerInterface $messageManager
    ) {
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @param $subject
     * @param $proceed
     * @return Redirect
     */
    public function aroundExecute($subject, $proceed)
    {
        $parameters = $subject->getRequest()->getParam("groups");
        if (isset($parameters["devhub_refundrequest_email_config"])) {
            $emails = '';
            if (isset($parameters["devhub_refundrequest_email_config"]["fields"]["admin_email"]["value"])) {
                $emails = $parameters["devhub_refundrequest_email_config"]["fields"]["admin_email"]["value"];
            }
            if ($emails != '') {
                $emailList = explode(",", $emails);
                $error = false;
                foreach ($emailList as $email) {
                    $checkEmail = trim($email);
                    if ($this->emailValidation($checkEmail)) {
                        $error = false;
                    } else {
                        $error = true;
                        break;
                    }
                }
                if ($error) {
                    $this->messageManager->addErrorMessage(__("One or more admin email has an invalid email format!"));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath(
                        'adminhtml/system_config/edit',
                        [
                            '_current' => ['section', 'website', 'store'],
                            '_nosid' => true
                        ]
                    );
                } else {
                    return $proceed();
                }
            } else {
                return $proceed();
            }
        }
        return $proceed();
    }

    /**
     * @param $email
     * @return bool
     */
    protected function emailValidation($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
}
