<?php
namespace Wdevs\InquireManager\Api;

/**
 * Class Wdevs\InquireManager\Api\ConfigInterface
 */
interface ConfigInterface
{
    const XML_PATH_ENABLE_TOP_LINK = 'general/enable_top_link';
    const XML_PATH_PAGE_HEADING = 'general/heading';
    const XML_PATH_FORM_DESCRIPTION = 'general/description';
    const XML_PATH_SUBMIT_BUTTON_TEXT = 'general/submit_button_text';
    const XML_PATH_DEFAULT_CUSTOMER_GROUP = 'general/default_customer_group';
    const XML_PATH_ENABLE_FILE_UPLOAD = 'general/enable_file_upload';
    
    const XML_PATH_META_TITLE = 'meta/meta_title';
    const XML_PATH_META_KEYWORDS = 'meta/meta_keywords';
    const XML_PATH_META_DESCRIPTION = 'meta/meta_description';
    
    const XML_PATH_EMAIL_OWNER_SUBJECT = 'email_templates/request_account_owner_subject';
    const XML_PATH_EMAIL_OWNER_EMAIL_TEMPLATE = 'email_templates/request_account_owner_email_template';
    const XML_PATH_EMAIL_OWNER_SEND_TO = 'email_templates/request_account_owner_send_to';
    const XML_PATH_EMAIL_OWNER_CUSTOM_SEND_TO = 'email_templates/request_account_owner_custom_owner_email';
    const XML_PATH_EMAIL_CUSTOMER_SUBJECT = 'email_templates/request_account_customer_subject';
    const XML_PATH_EMAIL_CUSTOMER_EMAIL_TEMPLATE = 'email_templates/request_account_customer_email_template';
    
    /**
     * Retrieve information from module configuration
     *
     * @param string $field
     * @param int|null $storeId
     *
     * @return mixed
     */
    public function getValue($field, $storeId = null);

    /**
     * Retrieve config flag from module configuration
     *
     * @param string $field
     * @param int|null $storeId
     *
     * @return boolean
     */
    public function isSetFlag($field, $storeId = null);
    
    /**
     * @param int $storeId
     * @return boolean
     */
    public function isEnableTopLink($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getPageTitle($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getFormDescription($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getSubmitButtonText($storeId = null);
    
    /**
     * @param int $storeId
     * @return int
     */
    public function getDefaultCustomerGroupId($storeId = null);
    
    /**
     * @param int $storeId
     * @return boolean
     */
    public function isEnableFileUpload($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getMetaTitle($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getMetaKeywords($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getMetaDescription($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailOwnerSubject($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailOwnerTemplate($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailOwnerSendTo($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailCustomerSubject($storeId = null);
    
    /**
     * @param int $storeId
     * @return string
     */
    public function getEmailCustomerTemplate($storeId = null);
    
}