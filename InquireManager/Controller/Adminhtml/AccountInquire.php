<?php
namespace Wdevs\InquireManager\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Error;
use Wdevs\InquireManager\Api\AccountInquireManagementInterface;
use Wdevs\InquireManager\Api\AccountInquireRepositoryInterface;

/**
 * Class Wdevs\InquireManager\Controller\Adminhtml\AccountInquire
 */
abstract class AccountInquire extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Wdevs_InquireManager::inquiremanager';
    
    /**
     * @var AccountInquireManagementInterface
     */
    protected $accountInquireManagement;
    
    /**
     * @var AccountInquireRepositoryInterface
     */
    protected $accountInquireRepository;

    /**
     * @param Context $context
     * @param AccountInquireManagementInterface $accountInquireManagement
     * @param AccountInquireRepositoryInterface $accountInquireRepository
     */
    public function __construct(
        Context $context,
        AccountInquireManagementInterface $accountInquireManagement,
        AccountInquireRepositoryInterface $accountInquireRepository
    ) {
        parent::__construct($context);
        $this->accountInquireManagement = $accountInquireManagement;
        $this->accountInquireRepository = $accountInquireRepository;
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Customer'), __('Customer'))
            ->addBreadcrumb(__('Inquire Manager'), __('Inquire Manager'));
        return $resultPage;
    }
    
    /**
     * @param array $messages
     */
    protected function _addSessionErrorMessages($messages)
    {
        $messages = (array)$messages;
        $session = $this->_getSession();
        
        $callback = function ($error) use ($session) {
            if (!$error instanceof Error) {
                $error = new Error($error);
            }
            $this->messageManager->addMessage($error);
        };
        array_walk_recursive($messages, $callback);
    }
}
