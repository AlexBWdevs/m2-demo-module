<?php

namespace Wdevs\InquireManager\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Wdevs\InquireManager\Api\AccountInquireRepositoryInterface;
use Wdevs\InquireManager\Api\Data\AccountInquireInterfaceFactory;
use Wdevs\InquireManager\Api\Data\AccountInquireSearchResultsInterfaceFactory;
use Wdevs\InquireManager\Model\ResourceModel\AccountInquire as ResourceAccountInquire;
use Wdevs\InquireManager\Model\ResourceModel\AccountInquire\CollectionFactory as AccountInquireCollectionFactory;

/**
 * Class Wdevs\InquireManager\Model\AccountInquireRepository
 */
class AccountInquireRepository implements AccountInquireRepositoryInterface
{
    /**
     * @var ResourceAccountInquire
     */
    protected $resource;

    /**
     * @var AccountInquireFactory
     */
    protected $accountInquireFactory;

    /**
     * @var AccountInquireCollectionFactory
     */
    protected $accountInquireCollectionFactory;

    /**
     * @var AccountInquireSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var AccountInquireInterfaceFactory
     */
    protected $dataAccountInquireFactory;

    /**
     * @var JoinProcessorInterface
     */
    protected $extensionAttributesJoinProcessor;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceAccountInquire $resource
     * @param AccountInquireFactory $accountInquireFactory
     * @param AccountInquireInterfaceFactory $dataAccountInquireFactory
     * @param AccountInquireCollectionFactory $accountInquireCollectionFactory
     * @param AccountInquireSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceAccountInquire $resource,
        AccountInquireFactory $accountInquireFactory,
        AccountInquireInterfaceFactory $dataAccountInquireFactory,
        AccountInquireCollectionFactory $accountInquireCollectionFactory,
        AccountInquireSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->accountInquireFactory = $accountInquireFactory;
        $this->accountInquireCollectionFactory = $accountInquireCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAccountInquireFactory = $dataAccountInquireFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire
    ) {
        $this->resource->save($accountInquire);
        return $accountInquire;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($accountInquireId)
    {
        $accountInquire = $this->accountInquireFactory->create();
        $this->resource->load($accountInquire, $accountInquireId);
        if (!$accountInquire->getId()) {
            throw new NoSuchEntityException(__('Account Inquire with id "%1" does not exist.', $accountInquireId));
        }
        return $accountInquire;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->accountInquireCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Wdevs\InquireManager\Api\Data\AccountInquireInterface $accountInquire
    ) {
        try {
            $accountInquireModel = $this->accountInquireFactory->create();
            $this->resource->load($accountInquireModel, $accountInquire->getId());
            $this->resource->delete($accountInquireModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Inquire: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($accountInquireId)
    {
        return $this->delete($this->getById($accountInquireId));
    }

    /**
     * {@inheritdoc}
     */
    public function getByEmail($email)
    {
        $accountInquire = $this->accountInquireFactory->create();
        $this->resource->load($accountInquire, $email, 'email');
        if (!$accountInquire->getId()) {
            return null;
        }
        return $accountInquire;
    }
}
