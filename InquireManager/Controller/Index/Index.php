<?php
namespace Wdevs\InquireManager\Controller\Index;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\UrlFactory;

/**
 * Class Wdevs\InquireManager\Controller\Index\Index
 */
class Index extends Action
{
    /**
     * @var Session
     */
    private $session;
    
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlModel;

    
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param UrlFactory $urlFactory
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        UrlFactory $urlFactory,
        PageFactory $resultPageFactory
    ) {
        $this->session = $customerSession;
        $this->urlModel = $urlFactory->create();
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Account Request Index, shows account request form
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if ($this->session->isLoggedIn()) {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl($this->urlModel->getBaseUrl());
            return $resultRedirect;
        }
        
        return $this->resultPageFactory->create();
    }
}
