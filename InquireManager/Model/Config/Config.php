<?php
namespace Wdevs\InquireManager\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Wdevs\InquireManager\Api\ConfigInterface;
use Wdevs\InquireManager\Model\OptionSource\EmailSendToOptionsProvider;
use Wdevs\CustomerSxapi\Gateway\Response\DeafaultShipToHandler;

/**
 * Class Wdevs\InquireManager\Model\Config\Config
 */
class Config implements ConfigInterface
{
    const DEFAULT_PATH_PATTERN = 'inquiremanager/%s';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var string|null
     */
    private $pathPattern;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param string $pathPattern
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        $pathPattern = self::DEFAULT_PATH_PATTERN
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->pathPattern = $pathPattern;
    }

    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getValue()
     */
    public function getValue($field, $storeId = null)
    {
        if ($this->pathPattern === null) {
            return null;
        }

        return $this->scopeConfig->getValue(
            sprintf($this->pathPattern, $field),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::isSetFlag()
     */
    public function isSetFlag($field, $storeId = null)
    {
        if ($this->pathPattern === null) {
            return null;
        }

        return $this->scopeConfig->isSetFlag(
            sprintf($this->pathPattern, $field),
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::isEnableTopLink()
     */
    public function isEnableTopLink($storeId = null)
    {
        return $this->isSetFlag(self::XML_PATH_ENABLE_TOP_LINK, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getPageTitle()
     */
    public function getPageTitle($storeId = null)
    {
        return $this->getValue(self::XML_PATH_PAGE_HEADING, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getFormDescription()
     */
    public function getFormDescription($storeId = null)
    {
        return $this->getValue(self::XML_PATH_FORM_DESCRIPTION, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getSubmitButtonText()
     */
    public function getSubmitButtonText($storeId = null)
    {
        return $this->getValue(self::XML_PATH_SUBMIT_BUTTON_TEXT, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getDefaultCustomerGroupId()
     */
    public function getDefaultCustomerGroupId($storeId = null)
    {
        return (int)$this->getValue(self::XML_PATH_DEFAULT_CUSTOMER_GROUP, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::isEnableFileUpload()
     */
    public function isEnableFileUpload($storeId = null)
    {
        return $this->isSetFlag(self::XML_PATH_ENABLE_FILE_UPLOAD, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getMetaTitle()
     */
    public function getMetaTitle($storeId = null)
    {
        return $this->getValue(self::XML_PATH_META_TITLE, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getMetaKeywords()
     */
    public function getMetaKeywords($storeId = null)
    {
        return $this->getValue(self::XML_PATH_META_KEYWORDS, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getMetaDescription()
     */
    public function getMetaDescription($storeId = null)
    {
        return $this->getValue(self::XML_PATH_META_DESCRIPTION, $storeId);
    }

    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getEmailOwnerSubject()
     */
    public function getEmailOwnerSubject($storeId = null)
    {
        return $this->getValue(self::XML_PATH_EMAIL_OWNER_SUBJECT, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getEmailOwnerTemplate()
     */
    public function getEmailOwnerTemplate($storeId = null)
    {
        return $this->getValue(self::XML_PATH_EMAIL_OWNER_EMAIL_TEMPLATE, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getEmailOwnerSendTo()
     */
    public function getEmailOwnerSendTo($storeId = null)
    {
        switch ($this->getValue(self::XML_PATH_EMAIL_OWNER_SEND_TO, $storeId)) {
            case EmailSendToOptionsProvider::SEND_TO_SPECIFIC_OWNER_EMAIL:
                return $this->getValue(self::XML_PATH_EMAIL_OWNER_CUSTOM_SEND_TO);
                break;
            case EmailSendToOptionsProvider::SEND_TO_DEFAULT_GENERAL_CONTACT:
            default:
                return $this->scopeConfig->getValue(
                    'trans_email/ident_general/email',
                    ScopeInterface::SCOPE_STORE,
                    $storeId
                );
                break;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getEmailCustomerSubject()
     */
    public function getEmailCustomerSubject($storeId = null)
    {
        return $this->getValue(self::XML_PATH_EMAIL_CUSTOMER_SUBJECT, $storeId);
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Api\ConfigInterface::getEmailCustomerTemplate()
     */
    public function getEmailCustomerTemplate($storeId = null)
    {
        return $this->getValue(self::XML_PATH_EMAIL_CUSTOMER_EMAIL_TEMPLATE, $storeId);
    }

}
