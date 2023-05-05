<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Helper;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class Email extends AbstractHelper
{
    /**
     * Helper Config Admin
     * @var Data
     */
    protected $helper;

    /**
     * Scope Config Interface
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * State Interface
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * Escaper
     * @var Escaper
     */
    protected $escaper;

    /**
     * Transport Builder
     *
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * Email constructor.
     * @param Context $context
     * @param Data $helper
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Context $context,
        Data $helper,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->helper = $helper;
        $this->scopeConfig = $context->getScopeConfig();
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->messageManager = $messageManager;
    }

    /**
     * @param $receivers
     * @param $emailTemplate
     * @param $templateVar
     */
    public function sendEmail($receivers, $emailTemplate, $templateVar)
    {
        try {
            $email = $this->helper->configSenderEmail();
            $emailValue = 'trans_email/ident_' . $email . '/email';
            $emailNameValue = 'trans_email/ident_' . $email . '/name';
            $emailNameSender = $this->scopeConfig->getValue($emailNameValue, ScopeInterface::SCOPE_STORE);
            $emailSender = $this->scopeConfig->getValue($emailValue, ScopeInterface::SCOPE_STORE);
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml($emailNameSender),
                'email' => $this->escaper->escapeHtml($emailSender),
            ];
            //Send Email
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($emailTemplate)
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($templateVar)
                ->setFromByScope($sender)
                ->addTo($receivers);
            $transport = $transport->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $this->messageManager
                ->addErrorMessage(__('Failed to send email, please try again later.' . $e->getMessage()));
            return;
        }
    }
}
