<?php

declare(strict_types=1);

namespace DevHub\RefundRequest\Model\Attribute\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ProductStatus implements OptionSourceInterface
{
    /**
     * Remind Status values
     */
    const YES = 1;
    const NO = 0;

    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::YES,  'label' => __('Yes')],
            ['value' => self::NO,  'label' => __('No')]
        ];
    }
}
