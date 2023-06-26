<?php
namespace Wdevs\InquireManager\Controller\Adminhtml\Inquire;

use Magento\Framework\Exception\LocalizedException;
use Wdevs\InquireManager\Controller\Adminhtml\AccountInquire;

/**
 * Class Wdevs\InquireManager\Controller\Adminhtml\Inquire\Delete
 */
class Delete extends AccountInquire
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $inquireId = $this->getRequest()->getParam('inquire_id');

        try {
            $this->accountInquireRepository->deleteById($inquireId);
            $this->messageManager->addSuccessMessage(__('Account Requests Removed Successfully'));

        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/inquire/listing');
    }
}
