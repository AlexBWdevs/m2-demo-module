<?php

namespace Wdevs\InquireManager\Model\OptionSource;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Wdevs\StoreBranches\Model\BranchGroupRepository;

/**
 * using in create customer process
 * Class BranchGroupsOptionsProvider
 * @package Wdevs\InquireManager\Model\OptionSource
 */
class BranchGroupsOptionsProvider implements OptionSourceInterface
{
    /**
     * @var BranchGroupRepository
     */
    private $branchGroupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param BranchGroupRepository $branchGroupRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        BranchGroupRepository $branchGroupRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->branchGroupRepository = $branchGroupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        $branchGroups = [];
        $branchGroups[] = [
            'value' => '',
            'label' => __('Select Branch Group')
        ];

        $searchCriteria = $this->searchCriteriaBuilder->create();

        /** @var \Wdevs\StoreBranches\Model\BranchGroup $branchGroup */
        foreach ($this->branchGroupRepository->getList($searchCriteria)->getItems() as $branchGroup) {
            $branchGroups[] = [
                'value' => $branchGroup->getId(),
                'label' => $branchGroup->getGroupName()
            ];
        }
        return $branchGroups;
    }
}
