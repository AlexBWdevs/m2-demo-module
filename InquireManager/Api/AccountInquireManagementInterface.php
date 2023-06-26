<?php
namespace Wdevs\InquireManager\Api;

/**
 * Class Wdevs\InquireManager\Api\AccountInquireManagementInterface
 */
interface AccountInquireManagementInterface
{
    /**
     * @param \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire
     * @param string $redirectUrl
     * @param string $attachment
     * @return \Wdevs\InquireManager\Api\Data\AccountInquireInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createAccountRequest(
        \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire,
        $attachment = null,
        $redirectUrl = ''
    );
    
    // method removed from demo
    
    /**
     * @param \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire
     * @return \Wdevs\InquireManager\Api\Data\AccountInquireInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createCustomerAccount(
        \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire
    );
}