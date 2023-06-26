<?php

namespace Wdevs\InquireManager\Model\OptionSource;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Used in request form
 * Class ContactTimeOption
 * @package Wdevs\InquireManager\Model\OptionSource
 */
class ContactTimeOptionsProvider implements OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('Select Time')],
            ['value' => 'Morning', 'label' => __('Morning')],
            ['value' => 'Afternoon', 'label' => __('Afternoon')],
            ['value' => 'Evening', 'label' => __('Evening')]
        ];
    }
}
