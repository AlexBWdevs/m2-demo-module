<?php

namespace Wdevs\InquireManager\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Wdevs\InquireManager\Api\Data\AccountInquireInterface;
use Wdevs\InquireManager\Api\Data\AccountInquireSearchResultsInterface;

/**
 * Class Wdevs\InquireManager\Api\AccountInquireRepositoryInterface
 */
interface AccountInquireRepositoryInterface
{
    /**
     * Save AccountInquire
     * @param AccountInquireInterface $accountInquire
     * @return AccountInquireInterface
     */
    public function save(
        AccountInquireInterface $accountInquire
    );

    /**
     * Retrieve AccountInquire
     * @param string $accountInquireId
     * @return AccountInquireInterface
     * @throws LocalizedException
     */
    public function getById($accountInquireId);

    /**
     * Retrieve AccountInquire matching the specified criteria.
     * @param SearchCriteriaInterface $searchCriteria
     * @return AccountInquireSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete AccountInquire
     * @param AccountInquireInterface $accountInquire
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        AccountInquireInterface $accountInquire
    );

    /**
     * Delete AccountInquire by ID
     * @param string $accountInquireId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($accountInquireId);

    /**
     * Retrieve AccountInquire
     * @param $email
     * @return AccountInquireInterface
     * @throws LocalizedException
     */
    public function getByEmail($email);
}
