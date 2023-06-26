<?php
namespace Wdevs\InquireManager\Controller\Adminhtml\Inquire;

use Magento\Framework\Exception\AbstractAggregateException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\Exception as ValidatorException;
use Wdevs\InquireManager\Controller\Adminhtml\AccountInquire;

/**
 * Class Wdevs\InquireManager\Controller\Adminhtml\Inquire\CreateCustomer
 */
class CreateCustomer extends AccountInquire
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Wdevs_InquireManager::create_customer';

    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $returnToEdit = false;
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        try {
            $inquireId = $this->getRequest()->getParam('inquire_id');
            /** @var \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire */
            $accountInquire = $this->accountInquireRepository->getById((int)$inquireId);
            
            $this->accountInquireManagement->createCustomerAccount($accountInquire);
            
            $this->messageManager->addSuccessMessage(__('You created the customer.'));
            
            $returnToEdit = true;
        } catch (ValidatorException $exception) {
            $messages = $exception->getMessages();
            if (empty($messages)) {
                $messages = $exception->getMessage();
            }
            $this->_addSessionErrorMessages($messages);
            $returnToEdit = true;
        } catch (AbstractAggregateException $exception) {
            $errors = $exception->getErrors();
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            $this->_addSessionErrorMessages($exception->getMessage());
            $returnToEdit = true;
        } catch (NoSuchEntityException $exception) {
            $this->_addSessionErrorMessages($exception->getMessage());
            $returnToEdit = false;
        } catch (LocalizedException $exception) {
            $this->_addSessionErrorMessages($exception->getMessage());
            $returnToEdit = true;
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage(
                $exception,
                __('Something went wrong while created customer')
                );
            $returnToEdit = true;
        }
        
        if ($returnToEdit) {
            $resultRedirect->setPath(
                '*/*/edit',
                ['inquire_id' => $inquireId, '_current' => true]
            );
            
        } else {
            $resultRedirect->setPath('*/inquire/listing');
        }
        return $resultRedirect;
    }

}
