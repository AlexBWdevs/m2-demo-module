<?php
namespace Wdevs\InquireManager\Model;

use Magento\Customer\Model\EmailNotification as CustomerEmailNotification;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Wdevs\InquireManager\Api\ConfigInterface;
use Wdevs\InquireManager\Api\Data\AccountInquireInterface;

/**
 * Class Wdevs\InquireManager\Model\EmailNotification
 */
class EmailNotification implements EmailNotificationInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;
    
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    
    /**
     * @var DataObjectProcessor
     */
    private $dataProcessor;
    
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @var SenderResolverInterface
     */
    private $senderResolver;
    
    /**
     * @var Escaper
     */
    private $escaper;
    
    /**
     * @var RegionFactory
     */
    private $regionFactory;
    
    /**
     * @param ConfigInterface $config
     * @param TransportBuilder $transportBuilder
     * @param DataObjectProcessor $dataProcessor
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param SenderResolverInterface $senderResolver
     * @param Escaper $escaper
     */
    public function __construct(
        ConfigInterface $config,
        TransportBuilder $transportBuilder,
        DataObjectProcessor $dataProcessor,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        SenderResolverInterface $senderResolver,
        Escaper $escaper,
        RegionFactory $regionFactory
    ) {
        $this->config = $config;
        $this->transportBuilder = $transportBuilder;
        $this->dataProcessor = $dataProcessor;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->senderResolver = $senderResolver;
        $this->escaper = $escaper;
        $this->regionFactory = $regionFactory;
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Model\EmailNotificationInterface::sendEmailNotificationToOwner()
     */
    public function sendEmailNotificationToOwner(AccountInquireInterface $accountInquire)
    {
        $store = $this->storeManager->getStore($accountInquire->getStoreId());
        
        $this->sendEmailTemplate(
            $accountInquire,
            ConfigInterface::XML_PATH_EMAIL_OWNER_EMAIL_TEMPLATE,
            CustomerEmailNotification::XML_PATH_REGISTER_EMAIL_IDENTITY,
            [
                'customer' => $this->getFullAccountRequestObject($accountInquire),
                'subject' => $this->escaper->escapeHtml($this->config->getEmailOwnerSubject($store)),
                'website' => $this->storeManager->getWebsite($accountInquire->getWebsite())->getName(),
                'branch_group_name' => $accountInquire->getBranchGroupName(),
                'region_name' => $this->getRegionName($accountInquire->getRegion()),
            ],
            $store->getId(),
            $this->config->getEmailOwnerSendTo($store)
        );
    }
    
    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Model\EmailNotificationInterface::sendEmailNotificationToCustomer()
     */
    public function sendEmailNotificationToCustomer(AccountInquireInterface $accountInquire)
    {
        $store = $this->storeManager->getStore($accountInquire->getStoreId());
        
        $this->sendEmailTemplate(
            $accountInquire,
            ConfigInterface::XML_PATH_EMAIL_CUSTOMER_EMAIL_TEMPLATE,
            CustomerEmailNotification::XML_PATH_REGISTER_EMAIL_IDENTITY,
            [
                'customer' => $this->getFullAccountRequestObject($accountInquire),
                'subject' => $this->escaper->escapeHtml($this->config->getEmailCustomerSubject($store)),
            ],
            $store->getId()
        );
    }
    
    /**
     * @param AccountInquireInterface $accountInquire
     * @param string $template
     * @param string $sender
     * @param array $templateParams
     * @param string $storeId
     * @param string $email
     */
    private function sendEmailTemplate(
        $accountInquire,
        $template,
        $sender,
        $templateParams = [],
        $storeId = null,
        $email = null
    ) {
        
        $templateId = $this->config->getValue($template, $storeId);
        if ($email === null) {
            $email = $accountInquire->getEmail();
        }
        
        /** @var array $from */
        $from = $this->senderResolver->resolve(
            $this->scopeConfig->getValue($sender, 'store', $storeId),
            $storeId
        );
        
        /**
         * @var \Magento\Framework\Mail\TransportInterface $transport
         */
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId)
        ->setTemplateOptions([
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
            'store' => $storeId
        ])
        ->setTemplateVars($templateParams)
        ->setFrom($from)
        ->addTo($email, $this->getCustomerName($accountInquire))
        ->getTransport();
        
        $transport->sendMessage();
    }
    
    /**
     * @param AccountInquireInterface $accountInquire
     * @return \Magento\Framework\DataObject
     */
    private function getFullAccountRequestObject(AccountInquireInterface $accountInquire)
    {
        $dataObject = new \Magento\Framework\DataObject();
        $accountRequestData = $this->dataProcessor
        ->buildOutputDataArray($accountInquire, \Wdevs\InquireManager\Api\Data\AccountInquireInterface::class);
        $dataObject->addData($accountRequestData);
        $dataObject->setData('name', $this->getCustomerName($accountInquire));
        return $dataObject;
    }
    
    /**
     * @param AccountInquireInterface $accountInquire
     * @return string
     */
    private function getCustomerName(AccountInquireInterface $accountInquire)
    {
        return $accountInquire->getFirstname() . ' ' . $accountInquire->getLastname();
    }
    
    /**
     * @param int $regionId
     * @return string
     */
    private function getRegionName($regionId)
    {
        /**
         * @var \Magento\Directory\Model\Region $region
         */
        $region = $this->regionFactory->create()->load($regionId);
        return $region->getName();
    }
    
}