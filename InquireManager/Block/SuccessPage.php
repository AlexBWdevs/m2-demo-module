<?php

namespace Wdevs\InquireManager\Block;

use Magento\Framework\View\Element\Template\Context;
use Wdevs\InquireManager\Api\ConfigInterface;

/**
 * Class for request account success page
 * Class SuccessPage
 * @package Wdevs\InquireManager\Block
 */
class SuccessPage extends \Magento\Framework\View\Element\Template
{
    /** @var ConfigInterface */
    protected $config;

    /**
     * RequestForm constructor.
     * @param Context $context
     * @param ConfigInterface $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigInterface $config,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $store = $this->_storeManager->getStore();
        $this->pageConfig->setRobots('NOINDEX,NOFOLLOW');
        $this->pageConfig->getTitle()->set(__('Request Account Success'));
        $this->pageConfig->setKeywords(__($this->config->getMetaKeywords($store)));
        $this->pageConfig->setDescription(__($this->config->getMetaDescription($store)));

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle(__('Request Account Success'));
        }
        return $this;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getSuccessMessage()
    {
        return __(
            'Thank You for Contacting Us. Your inquiry has been submitted successfully and will respond to you shortly.'
        );
    }
}
