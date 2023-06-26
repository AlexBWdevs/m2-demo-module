<?php
namespace Wdevs\InquireManager\Model\ResourceModel;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Validator\Exception as ValidatorException;
use Magento\Store\Model\StoreManagerInterface;
use Wdevs\StoreBranches\Model\BranchGroupRepository;

/**
 * Class Wdevs\InquireManager\Model\ResourceModel\AccountInquire
 */
class AccountInquire extends AbstractDb
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @var BranchGroupRepository
     */
    private $branchGroupRepository;
    
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\Model\ResourceModel\Db\AbstractDb::__construct()
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        BranchGroupRepository $branchGroupRepository,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->branchGroupRepository = $branchGroupRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('demo', 'inquire_id');
    }
    
    /**
     * {@inheritDoc}
     * @see \Magento\Framework\Model\ResourceModel\Db\AbstractDb::_beforeSave()
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {

        // LOGIC REMOVED FROM DEMO
        $result = false;
        //

        if ($result) {
            throw new AlreadyExistsException(
                __('An account request with the same email address already exists.')
            );
        }
        return $this;
    }
}
