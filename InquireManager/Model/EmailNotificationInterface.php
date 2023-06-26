<?php
namespace Wdevs\InquireManager\Model;

use Wdevs\InquireManager\Api\Data\AccountInquireInterface;

/**
 * Class Wdevs\InquireManager\Model\EmailNotificationInterface
 */
interface EmailNotificationInterface
{
    /**
     * @param AccountInquireInterface $accountInquire
     */
    public function sendEmailNotificationToOwner(AccountInquireInterface $accountInquire);
    
    /**
     * @param AccountInquireInterface $accountInquire
     */
    public function sendEmailNotificationToCustomer(AccountInquireInterface $accountInquire);
}