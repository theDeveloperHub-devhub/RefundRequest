<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model\Attribute\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SelectOption implements OptionSourceInterface
{
    /**
     * Remind Status values
     */
    const ENABLE = 0;
    const DISABLE = 1;

    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ENABLE,  'label' => __('Enable')],
            ['value' => self::DISABLE,  'label' => __('Disable')]
        ];
    }
}
