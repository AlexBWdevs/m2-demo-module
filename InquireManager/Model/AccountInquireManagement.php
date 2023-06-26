<?php
namespace Wdevs\InquireManager\Model;

use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\DataObject\Copy;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Validator\Exception as ValidatorException;
use Psr\Log\LoggerInterface as PsrLogger;
use Wdevs\CustomerSxapi\Service\CustomerServiceManager;
use Wdevs\InquireManager\Api\AccountInquireManagementInterface;
use Wdevs\InquireManager\Api\AccountInquireRepositoryInterface;
use Wdevs\InquireManager\Api\ConfigInterface;
use Wdevs\InquireManager\Api\Data\AccountInquireInterface;
use Wdevs\InquireManager\Model\AttachmentManagement;
use Wdevs\InquireManager\Model\OptionSource\RequestStatus;
use Wdevs\InquireManager\Model\EmailNotificationInterface;
use Wdevs\StoreBranches\Model\BranchGroupRepository;
use Wdevs\Sxapi\Gateway\Command\CommandException;

/**
 * Class Wdevs\InquireManager\Model\AccountInquireManagement
 */
class AccountInquireManagement implements AccountInquireManagementInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;
    
    /**
     * @var AccountInquireRepositoryInterface
     */
    private $accountInquireRepository;
    
    /**
     * @var AttachmentManagement
     */
    private $attachmentManagement;
    
    /**
     * @var EmailNotificationInterface
     */
    private $emailNotification;
    
    /**
     * @var CustomerServiceManager
     */
    private $customerServiceManager;
    
    /**
     * @var CustomerInterfaceFactory
     */
    private $customerFactory;
    
    /**
     * @var CustomerInterface
     */
    private $customer;
    
    /**
     * @var BranchGroupRepository
     */
    private $branchGroupRepository;
    
    /**
     * @var Copy
     */
    private $objectCopyService;
    
    /**
     * @var AddressInterfaceFactory
     */
    private $addressFactory;
    
    /**
     * @var AccountManagementInterface
     */
    private $accountManagement;
    
    /**
     * @var PsrLogger
     */
    private $logger;
    
    /**
     * @param AccountInquireRepositoryInterface $accountInquireRepository
     * @param AttachmentManagement $attachmentManagement
     * @param EmailNotificationInterface $emailNotification
     * @param CustomerServiceManager $customerServiceManager
     * @param CustomerInterfaceFactory $customerFactory
     * @param BranchGroupRepository $branchGroupRepository
     * @param Copy $objectCopyService
     * @param AddressInterfaceFactory $addressFactory
     * @param AccountManagementInterface $accountManagement
     * @param PsrLogger $logger
     */
    public function __construct(
        ConfigInterface $config,
        AccountInquireRepositoryInterface $accountInquireRepository,
        AttachmentManagement $attachmentManagement,
        EmailNotificationInterface $emailNotification,
        CustomerServiceManager $customerServiceManager,
        CustomerInterfaceFactory $customerFactory,
        BranchGroupRepository $branchGroupRepository,
        Copy $objectCopyService,
        AddressInterfaceFactory $addressFactory,
        AccountManagementInterface $accountManagement,
        PsrLogger $logger
    ) {
        $this->config = $config;
        $this->accountInquireRepository = $accountInquireRepository;
        $this->attachmentManagement = $attachmentManagement;
        $this->emailNotification = $emailNotification;
        $this->customerServiceManager = $customerServiceManager;
        $this->customerFactory = $customerFactory;
        $this->branchGroupRepository = $branchGroupRepository;
        $this->objectCopyService = $objectCopyService;
        $this->addressFactory = $addressFactory;
        $this->accountManagement = $accountManagement;
        $this->logger = $logger;
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\AccountInquireManagementInterface::createAccountRequest()
     */
    public function createAccountRequest(
        AccountInquireInterface $accountInquire,
        $attachment = null,
        $redirectUrl = ''
    ) {
        if ($attachment) {
            $this->uploadAttachment($accountInquire, $attachment);
        }
        
        $accountInquire->setStatus(RequestStatus::ACCOUNT_REQUESTED);
        
        try {
            $accountInquire = $this->accountInquireRepository->save($accountInquire);
        } catch (AlreadyExistsException $e) {
            throw new InputMismatchException(
                __('An acccount request with the same email address already exists.')
            );
        } catch (LocalizedException $e) {
            throw $e;
        }
        
        $accountInquire = $this->accountInquireRepository->getById($accountInquire->getId());
        $this->sendEmailNotification($accountInquire, $redirectUrl);
        
        return $accountInquire;
    }

    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\AccountInquireManagementInterface::createCustomerAccount()
     */
    public function createCustomerAccount(AccountInquireInterface $accountInquire)
    {
        $this->validateAccountInquireForCreateCustomer($accountInquire);
        try {

            $customer = $this->getCustomer();
            
            $this->objectCopyService->copyFieldsetToTarget(
                'inquire_manager_account_request',
                'to_customer_account',
                $accountInquire,
                $customer
            );
            
            $defaultCustomerGroupId = $this->config->getDefaultCustomerGroupId($accountInquire->getStoreId());
            if (!$customer->getGroupId() && $defaultCustomerGroupId) {
                $customer->setGroupId($defaultCustomerGroupId);
            }
            

            // LOGIC REMOVED FROM DEMO
            
            /**
             * @var \Magento\Customer\Api\Data\AddressInterface $address
             */
            $address = $this->addressFactory->create();
            
            $this->objectCopyService->copyFieldsetToTarget(
                'inquire_manager_account_request',
                'to_customer_address',
                $accountInquire,
                $address
            );

            // LOGIC REMOVED FROM DEMO
            
            $this->accountManagement->createAccount($customer);
            
            $accountInquire->setStatus(RequestStatus::ACCOUNT_CREATED);
            $this->accountInquireRepository->save($accountInquire);
            
        } catch (ValidatorException $e) {
            
            if ($e->getPrevious() instanceof CommandException) {
                // LOGIC REMOVED FROM DEMO
            }
            
            throw $e;
        } catch (LocalizedException $e) {
            throw $e;
        }
        
        return $accountInquire;
        
    }

    // method removed from demo
    
    /**
     * @param AccountInquireInterface $accountInquire
     * @param string $attachment
     * @return \Wdevs\InquireManager\Model\AccountInquireManagement
     */
    private function uploadAttachment(
        AccountInquireInterface $accountInquire,
        $attachment
    ) {
        $uploadedResult = $this->attachmentManagement->uploadAttachment($attachment);
        if ($uploadedResult && isset($uploadedResult['file'])) {
            $accountInquire->getAttachmentFilename($uploadedResult['file']);
        }
        return $this;
    }
    
    /**
     * @param AccountInquireInterface $accountInquire
     * @param string $redirectUrl
     */
    private function sendEmailNotification(AccountInquireInterface $accountInquire, $redirectUrl)
    {
        $this->sendEmailNotificationToOwner($accountInquire);
        $this->sendEmailNotificationToCustomer($accountInquire);
    }
    
    /**
     * @param AccountInquireInterface $accountInquire
     */
    private function sendEmailNotificationToOwner(AccountInquireInterface $accountInquire)
    {
        try {
            $this->emailNotification->sendEmailNotificationToOwner($accountInquire);
        } catch (MailException $e) {
            // If we are not able to send a email, this should be ignored
            $this->logger->critical($e);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
    
    /**
     * @param AccountInquireInterface $accountInquire
     */
    private function sendEmailNotificationToCustomer(AccountInquireInterface $accountInquire)
    {
        try {
            $this->emailNotification->sendEmailNotificationToCustomer($accountInquire);
        } catch (MailException $e) {
            // If we are not able to send a email, this should be ignored
            $this->logger->critical($e);
        } catch (\Exception $e) {
            $this->logger->error($e);
        }
    }
    
    /**
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function getCustomer()
    {
        if ($this->customer == null) {
            $this->customer = $this->customerFactory->create();
        }
        
        return $this->customer;
    }
    
    /**
     * @param AccountInquireInterface $accountInquire
     * @throws InputException
     */
    private function validateAccountInquireForCreateCustomer(AccountInquireInterface $accountInquire)
    {
        $errorMessages = [];
        if (!$accountInquire->getAccountNumber()) {
            $errorMessages[] = __(
                '"%fieldName" is required. Enter and try again.',
                ['fieldName' => 'Account Number']
            );
        }
        
        if (!$accountInquire->getWebsite()) {
            $errorMessages[] = __(
                '"%fieldName" is required. Enter and try again.',
                ['fieldName' => 'Requested Branch']
            );
        }
        
        if (!$accountInquire->getBranchGroup()) {
            $errorMessages[] = __(
                '"%fieldName" is required. Enter and try again.',
                ['fieldName' => 'Branch Group']
            );
        }
        
        if ($accountInquire->getWebsite() && $accountInquire->getBranchGroup()) {
            $branchGroupId = $this->branchGroupRepository->getBranchGroupIdByWebsiteId($accountInquire->getWebsite());
            if ($branchGroupId != $accountInquire->getBranchGroup()) {
                $errorMessages[] = __(
                    'Branch Group or Requested Branch mismatched. Enter and try again.'
                );
            }
        }
        
        if (count($errorMessages)) {
            $inputException = new InputException();
            foreach ($errorMessages as $errorMessage) {
                $inputException->addError($errorMessage);
            }
            throw $inputException;
        }
    }

    
}