<?php

namespace Wdevs\InquireManager\Block;

use Magento\Directory\Block\Data as Directory;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Session\Generic as SessionGeneric;
use Magento\Framework\View\Element\Template\Context;
use Wdevs\InquireManager\Api\ConfigInterface;
use Wdevs\InquireManager\Model\OptionSource\ContactTimeOptionsProvider;

/**
 * Class for request account frontend form
 * Class RequestForm
 * @package Wdevs\InquireManager\Block
 */
class RequestForm extends \Magento\Framework\View\Element\Template
{

    /**
     * @var string
     */
    private $submitUrl = 'request-account/inquire/save';

    /**
     * @var string
     */
    private $countryRegionUrl = 'request-account/ajax/country';

    /**
     * @var SessionGeneric
     */
    protected $formSession;

    /**
     * @var ConfigInterface $config
     */
    protected $config;

    /**
     * @var ContactTimeOptionsProvider $contactTimeOption
     */
    protected $contactTimeOption;

    /**
     * @var Directory $directoryBlock
     */
    protected $directoryBlock;

    /**
     * @var FormKey $formKey
     */
    private $formKey;

    /**
     * RequestForm constructor.
     * @param Context $context
     * @param SessionGeneric $formSession
     * @param ContactTimeOptionsProvider $contactTimeOption
     * @param ConfigInterface $config
     * @param FormKey $formKey
     * @param Directory $directory
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionGeneric $formSession,
        ContactTimeOptionsProvider $contactTimeOption,
        ConfigInterface $config,
        FormKey $formKey,
        Directory $directory,
        array $data = []
    ) {
        $this->contactTimeOption = $contactTimeOption;
        $this->formSession = $formSession;
        $this->config = $config;
        $this->formKey = $formKey;
        $this->directoryBlock = $directory;
        
        parent::__construct($context, $data);
        
        $this->_isScopePrivate = false;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $store = $this->_storeManager->getStore();
        $this->pageConfig->getTitle()->set(__($this->config->getMetaTitle($store)));
        $this->pageConfig->setKeywords(__($this->config->getMetaKeywords($store)));
        $this->pageConfig->setDescription(__($this->config->getMetaDescription($store)));

        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle(__($this->config->getPageTitle($store)));
        }

        if ($this->formSession->getFormData()) {
            $this->addData($this->formSession->getFormData());
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return string
     */
    public function getFormDescription()
    {
        return $this->config->getFormDescription($this->_storeManager->getStore());
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl($this->submitUrl);
    }

    /**
     * @return string
     */
    public function getSubmitButtonText()
    {
        return $this->config->getSubmitButtonText($this->_storeManager->getStore());
    }

    /**
     * @return array
     */
    public function getContactTimeOptions()
    {
        return $this->contactTimeOption->toOptionArray();
    }

    /**
     * @return string
     */
    public function getCountriesHtml()
    {
        return $this->directoryBlock->getCountryHtmlSelect($this->getCountryId(), 'country');
    }

    /**
     * @return string
     */
    public function getRegionHtml()
    {
        if ($this->getRegion()) {
            $this->directoryBlock->setRegionId($this->getRegion());
        }
        return $this->directoryBlock->getRegionHtmlSelect();
    }

    /**
     * @return string
     */
    public function getCountryAction()
    {
        return $this->getUrl($this->countryRegionUrl, ['_secure' => true]);
    }

    /**
     * @param array $option
     * @param string $formValue
     * @return string
     */
    public function isOptionSelected($option, $formValue)
    {
        if ($option['value'] === $formValue) {
            return ' selected="selected"';
        }
        return '';
    }

    /**
     * @return bool
     */
    public function isFileUploaderEnabled()
    {
        return $this->config->isEnableFileUpload($this->_storeManager->getStore());
    }
}
