<?php
namespace Wdevs\InquireManager\Model;

use Magento\Framework\Api\DataObjectHelper;
use Wdevs\InquireManager\Api\Data\AccountInquireInterface;
use Wdevs\InquireManager\Api\Data\AccountInquireInterfaceFactory;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Wdevs\InquireManager\Model\AccountInquire
 */
class AccountInquire extends AbstractModel implements AccountInquireInterface
{
    /**
     * @var AccountInquireInterfaceFactory
     */
    protected $accountInquireDataFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var string
     */
    protected $_eventPrefix = 'wdevs_inquiremanager_inquires';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Wdevs\InquireManager\Model\ResourceModel\AccountInquire::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::INQUIRE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($inquire_id)
    {
        $this->setData(self::INQUIRE_ID, $inquire_id);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFirstname()
    {
        return $this->getData(self::FIRSTNAME);
    }

    /**
     * @inheritDoc
     */
    public function setFirstname($firstname)
    {
        $this->setData(self::FIRSTNAME, $firstname);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLastname()
    {
        return $this->getData(self::LASTNAME);
    }

    /**
     * @inheritDoc
     */
    public function setLastname($lastname)
    {
        $this->setData(self::LASTNAME, $lastname);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCompanyName()
    {
        return $this->getData(self::COMPANY_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setCompanyName($company_name)
    {
        $this->setData(self::COMPANY_NAME, $company_name);
        return $this;
    }


    //  REMOVED FOR DEMO

    /**
     * Retrieve AccountInquire model with AccountInquire data
     * @return AccountInquireInterface
     */
    public function getDataModel()
    {
        $accountInquireData = $this->getData();
        $accountInquireDataObject = $this->accountInquireDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $accountInquireDataObject,
            $accountInquireData,
            AccountInquireInterface::class
        );
        return $accountInquireDataObject;
    }
    
    /**
     * @return string
     */
    public function getBranchGroupName()
    {
        return $this->_getResource()->getBranchGroupName($this);
    }
}
