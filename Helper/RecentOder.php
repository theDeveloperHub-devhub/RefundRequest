<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Helper;

class RecentOder
{

    /**
     * @var Data
     */
    protected $helperConfigAdmin;

    /**
     * RecentOder constructor.
     * @param Data $helperConfigAdmin
     */
    public function __construct(
        Data $helperConfigAdmin
    ) {
        $this->helperConfigAdmin = $helperConfigAdmin;
    }

    //General config admin
    /**
     * @return string
     */
    public function getTemplate()
    {
        if ($this->helperConfigAdmin->getConfigEnableModule()) {
            $template =  'DevHub_RefundRequest::order/recent.phtml';
        } else {
            $template = 'Magento_Sales::order/recent.phtml';
        }

        return $template;
    }

    /**
     * @return string
     */
    public function getTemplateMyOder()
    {
        if ($this->helperConfigAdmin->getConfigEnableModule()) {
            $template =  'DevHub_RefundRequest::order/history.phtml';
        } else {
            $template = 'Magento_Sales::order/history.phtml';
        }
        return $template;
    }
}
