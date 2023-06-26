<?php
namespace Wdevs\InquireManager\Controller\Adminhtml\Inquire;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Message\Error;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\Exception as ValidatorException;
use Wdevs\InquireManager\Controller\Adminhtml\AccountInquire;
use Wdevs\InquireManager\Model\OptionSource\RequestStatus;

/**
 * Class Wdevs\InquireManager\Controller\Adminhtml\Inquire\Save
 */
class Save extends AccountInquire implements HttpPostActionInterface
{
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $returnToEdit = false;
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if (!$this->getRequest()->isPost()) {
            $resultRedirect->setPath('*/inquire/listing');
            return $resultRedirect;
        }
        
        $data = $this->getRequest()->getPostValue();
        
        try {
            $inquireId = $this->getRequest()->getParam('inquire_id');
            /** @var \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire */
            $accountInquire = $this->accountInquireRepository->getById((int)$inquireId);
            
            if ($data['account_number'] != $accountInquire->getAccountNumber()) {
                // LOGIC REMOVED FROM DEMO
            }
            
            $data['ignore_email_validation_flag'] = ($data['email'] == $accountInquire->getEmail());
            
            $accountInquire->setData($data);
            
            $this->accountInquireRepository->save($accountInquire);
            $this->messageManager->addSuccessMessage(__('You saved the account request.'));
            $returnToEdit = true;
        } catch (ValidatorException $exception) {
            $messages = $exception->getMessages();
            if (empty($messages)) {
                $messages = $exception->getMessage();
            }
            $this->_addSessionErrorMessages($messages);
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
                __('Something went wrong while save account request')
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
