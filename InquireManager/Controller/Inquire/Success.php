<?php

namespace Wdevs\InquireManager\Controller\Inquire;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Success extends \Magento\Framework\App\Action\Action
{
    /** @var  PageFactory */
    protected $resultPageFactory;

    /**
     * Success constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Show Success Page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
