<?php
namespace Wdevs\InquireManager\Controller\Adminhtml\Inquire;

use Magento\Framework\Controller\ResultFactory;
use Wdevs\InquireManager\Controller\Adminhtml\AccountInquire;

/**
 * Class Wdevs\InquireManager\Controller\Adminhtml\Inquire\Listing
 */
class Listing extends AccountInquire
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        
        $this->initPage($resultPage)
            ->addBreadcrumb(
                __('Account Requests'),
                __('Account Requests List')
            );
        $resultPage->getConfig()->getTitle()->prepend(__('Account Requests'));
        return $resultPage;
    }
}
