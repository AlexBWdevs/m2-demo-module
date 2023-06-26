<?php
namespace Wdevs\InquireManager\Api\Data;

/**
 * Class Wdevs\InquireManager\Api\Data\AccountInquireSearchResultsInterface
 */
interface AccountInquireSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get AccountInquire list.
     * @return AccountInquireInterface[]
     */
    public function getItems();

    /**
     * Set AccountInquire list.
     * @param AccountInquireInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
