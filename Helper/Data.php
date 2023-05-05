<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * Config for enable module
     */
    const DEVHUB_CONFIG_ENABLE_MODULE = 'devhub_refundrequest/devhub_refundrequest_general/enable';

    /**
     * Config for order refund
     */
    const DEVHUB_CONFIG_ORDER_REFUND = 'devhub_refundrequest/devhub_refundrequest_general/canrefund';

    /**
     * Config for title popup
     */
    const DEVHUB_CONFIG_POPUP_TITLE = 'devhub_refundrequest/devhub_refundrequest_config/popup_title';

    /**
     * Config for enable dropdown
     */
    const DEVHUB_CONFIG_ENABLE_DROPDOWN = 'devhub_refundrequest/devhub_refundrequest_config/enable_dropdown';

    /**
     * Config for dropdown title
     */
    const DEVHUB_CONFIG_DROPDOWN_TITLE = 'devhub_refundrequest/devhub_refundrequest_config/dropdown_title';

    /**
     * Config for enable option
     */
    const DEVHUB_CONFIG_ENABLE_OPTION = 'devhub_refundrequest/devhub_refundrequest_config/enable_option';

    /**
     * Config for option title
     */
    const DEVHUB_CONFIG_OPTION_TITLE = 'devhub_refundrequest/devhub_refundrequest_config/option_title';

    /**
     * Config for detail title
     */
    const DEVHUB_CONFIG_DETAIL_TITLE = 'devhub_refundrequest/devhub_refundrequest_config/detail_title';

    /**
     * Config for config title
     */
    const DEVHUB_CONFIG_POPUP_DESCRIPTION = 'devhub_refundrequest/devhub_refundrequest_config/popup_description';

    /**
     * Config for admin email
     */
    const DEVHUB_CONFIG_ADMIN_EMAIL = 'devhub_refundrequest/devhub_refundrequest_email_config/admin_email';

    /**
     * Config for email template
     */
    const DEVHUB_CONFIG_EMAIL_TEMPLATE = 'devhub_refundrequest/devhub_refundrequest_email_config/email_template';

    /**
     * Config for email sender
     */
    const DEVHUB_CONFIG_EMAIL_SENDER = 'devhub_refundrequest/devhub_refundrequest_email_config/email_sender';

    /**
     * Config for accept email
     */
    const DEVHUB_CONFIG_ACCEPT_EMAIL = 'devhub_refundrequest/devhub_refundrequest_email_config/accept_email';

    /**
     * Config for reject email
     */
    const DEVHUB_CONFIG_REJECT_EMAIL = 'devhub_refundrequest/devhub_refundrequest_email_config/reject_email';

    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
    }

    /**
     * Get Config Enable Module
     *
     * @return string
     */
    public function getConfigEnableModule()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_ENABLE_MODULE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getOrderRefund()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_ORDER_REFUND,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Module
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_POPUP_DESCRIPTION,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Module
     *
     * @return string
     */
    public function getPopupModuleTitle()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_POPUP_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Enable Dropdown In Modal Popup
     *
     * @return string
     */
    public function getConfigEnableDropdown()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_ENABLE_DROPDOWN,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Dropdown Modal Popup
     *
     * @return string
     */
    public function getDropdownTitle()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_DROPDOWN_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Enable Yes/No Option
     *
     * @return string
     */
    public function getConfigEnableOption()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_ENABLE_OPTION,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Yes/No Option
     *
     * @return string
     */
    public function getOptionTitle()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_OPTION_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config
     *
     * @return string
     */
    public function getDetailTitle()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_DETAIL_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getAdminEmail()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_ADMIN_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getEmailTemplate()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function configSenderEmail()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getRejectEmailTemplate()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_REJECT_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getAcceptEmailTemplate()
    {
        return $this->scopeConfig->getValue(
            self::DEVHUB_CONFIG_ACCEPT_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

}
