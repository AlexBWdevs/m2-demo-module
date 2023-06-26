<?php
namespace Wdevs\InquireManager\Plugin\Customer\Model;

use Magento\Customer\Model\Registration;
use Magento\Store\Model\StoreManagerInterface;
use Wdevs\InquireManager\Api\ConfigInterface;

/**
 * Class Wdevs\InquireManager\Plugin\Customer\Model\RegistrationPlugin
 */
class RegistrationPlugin
{
    /**
     * @var ConfigInterface
     */
    private $config;
    
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @param ConfigInterface $config
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ConfigInterface $config,
        StoreManagerInterface $storeManager
    ) {
        $this->config = $config;
        $this->storeManager = $storeManager;
    }
    /**
     * @param Registration $subject
     * @param boolean $result
     * @return bool
     */
    public function afterIsAllowed(Registration $subject, $result)
    {
        if ($this->config->isEnableTopLink($this->storeManager->getStore())) {
            return false;
        }
        
        return $result;
    }
}
