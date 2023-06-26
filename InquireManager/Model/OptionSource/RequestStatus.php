<?php
namespace Wdevs\InquireManager\Model\OptionSource;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Wdevs\InquireManager\Model\OptionSource\RequestStatus
 */
class RequestStatus implements OptionSourceInterface
{
    const ACCOUNT_REQUESTED = 1;
    const ACCOUNT_CREATED = 4;
    
    /**
     * @var array
     */
    private $options = [];
    
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\Data\OptionSourceInterface::toOptionArray()
     */
    public function toOptionArray()
    {
        if (empty($this->options)) {
            // LOGIC REMOVED FROM DEMO
        }
        return $this->options;
    }
    
    /**
     * @return string[]
     */
    public function getAlowedCreateCustomerStatus()
    {
        return [
            static::ACCOUNT_REQUESTED,
        ];
    }
}