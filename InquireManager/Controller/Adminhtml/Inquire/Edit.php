<?php
namespace Wdevs\InquireManager\Controller\Adminhtml\Inquire;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Wdevs\InquireManager\Controller\Adminhtml\AccountInquire;

/**
 * Class Wdevs\InquireManager\Controller\Adminhtml\Inquire\Edit
 */
class Edit extends AccountInquire
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        try {
            $inquireId = $this->getRequest()->getParam('inquire_id');
            $accountInquire = $this->accountInquireRepository->getById((int)$inquireId);
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addException($e, __('This Account Request no longer exists.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/inquire/listing');
            return $resultRedirect;
        }
        
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage)->addBreadcrumb(
            __('Edit Account Request'),
            __('Edit Account Request')
        );
        $resultPage->getConfig()->getTitle()->prepend(
            __('Account Request for %1', $accountInquire->getEmail())
        );
        
        return $resultPage;
    }
}
