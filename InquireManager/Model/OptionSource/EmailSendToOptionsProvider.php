<?php

namespace Wdevs\InquireManager\Model\OptionSource;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Used in creating options for module config value selection Request Account Email Template/Email Send to
 * Class EmailSendTo
 * @package Wdevs\InquireManager\Model\OptionSource
 */
class EmailSendToOptionsProvider implements OptionSourceInterface
{
    const SEND_TO_DEFAULT_GENERAL_CONTACT = 0;
    const SEND_TO_SPECIFIC_OWNER_EMAIL = 1;
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::SEND_TO_DEFAULT_GENERAL_CONTACT, 'label' => __('Default General Contact')],
            ['value' => self::SEND_TO_SPECIFIC_OWNER_EMAIL, 'label' => __('Specified Owner Email')]
        ];
    }
}
