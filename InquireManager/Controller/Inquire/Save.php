<?php
declare(strict_types=1);

namespace Wdevs\InquireManager\Controller\Inquire;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Phrase;
use Magento\Framework\Session\Generic as SessionGeneric;
use Magento\Framework\UrlFactory;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Wdevs\InquireManager\Api\AccountInquireManagementInterface;
use Wdevs\InquireManager\Api\Data\AccountInquireInterfaceFactory;

/**
 * Class Wdevs\InquireManager\Controller\Inquire\Save
 */
class Save extends Action implements CsrfAwareActionInterface, HttpPostActionInterface
{
    /**
     * @var PostDataProcessor
     */
    private $dataProcessor;
    
    /**
     * @var AccountInquireInterfaceFactory
     */
    private $accountInquireFactory;
    
    /**
     * @var AccountInquireManagementInterface
     */
    private $accountInquireManagement;
    
    /**
     * @var SessionGeneric
     */
    private $formSession;
    
    /**
     * @var CustomerSession
     */
    private $customerSession;
    
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlModel;
    
    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param AccountInquireInterfaceFactory $accountInquireFactory
     * @param AccountInquireManagementInterface $accountInquireManagement
     * @param SessionGeneric $formSession
     * @param CustomerSession $customerSession
     * @param UrlFactory $urlFactory
     */
    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        AccountInquireInterfaceFactory $accountInquireFactory,
        AccountInquireManagementInterface $accountInquireManagement,
        SessionGeneric $formSession,
        CustomerSession $customerSession,
        UrlFactory $urlFactory
    ) {
        $this->dataProcessor = $dataProcessor;
        $this->accountInquireFactory = $accountInquireFactory;
        $this->accountInquireManagement = $accountInquireManagement;
        $this->formSession = $formSession;
        $this->customerSession = $customerSession;
        $this->urlModel = $urlFactory->create();
        parent::__construct($context);
    }
    
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\Action\Action::dispatch()
     */
    public function dispatch(RequestInterface $request)
    {
        if (!$request->isDispatched()) {
            return parent::dispatch($request);
        }
        
        if ($request->getActionName() == 'save' && $request->isPost()) {
            $this->formSession->setFormData($request->getPostValue());
            if ($this->_redirect->getRefererUrl()) {
                $url = $this->_redirect->getRefererUrl();
            } else {
                $url = $this->urlModel->getUrl('*', ['_secure' => true]);
            }
            
            $this->formSession->setRedirectUrl($url);
        }
        
        return parent::dispatch($request);
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\CsrfAwareActionInterface::createCsrfValidationException()
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $url = $this->urlModel->getUrl('*', ['_secure' => true]);
        $resultRedirect->setUrl($this->_redirect->error($url));
        
        return new InvalidRequestException(
            $resultRedirect,
            [new Phrase('Invalid Form Key. Please refresh the page.')]
        );
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\CsrfAwareActionInterface::validateForCsrf()
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($this->customerSession->isLoggedIn()) {
            $this->formSession->clearStorage();
            $resultRedirect->setUrl($this->urlModel->getBaseUrl());
            return $resultRedirect;
        }
        
        if (!$this->getRequest()->isPost()) {
            $url = $this->urlModel->getUrl('*', ['_secure' => true]);
            return $this->resultRedirectFactory->create()
            ->setUrl($this->_redirect->error($url));
        }
        
        $data = $this->getRequest()->getPostValue();
        try {
            
            $attachment = $this->getRequest()->getFiles('attachment');
            
            $data = $this->dataProcessor->filter($data);
            
            /** @var \Wdevs\InquireManager\Model\AccountInquire $accountInquire */
            $accountInquire = $this->accountInquireFactory->create();
            
            $accountInquire->setData($data);
            
            $this->accountInquireManagement->createAccountRequest($accountInquire, $attachment);

            $this->formSession->clearStorage();
            $this->messageManager->addSuccessMessage(
                __(
                    'Thank You for Contacting Us.'
                    . ' Your inquiry has been submitted successfully and will respond to you shortly.'
                )
            );
            $url = $this->urlModel->getBaseUrl();
            return $resultRedirect->setUrl($url);
            
        } catch (StateException $e) {
            $this->messageManager->addComplexErrorMessage(
                'accountRequestAlreadyExistsErrorMessage',
                [
                    'url' => $this->urlModel->getUrl('customer/account/forgotpassword'),
                ]
            );
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            foreach ($e->getErrors() as $error) {
                $this->messageManager->addErrorMessage($error->getMessage());
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the request.'));
        }
        
        $defaultUrl = $this->urlModel->getUrl('*', ['_secure' => true]);
        return $resultRedirect->setUrl($this->_redirect->error($defaultUrl));
    }
}