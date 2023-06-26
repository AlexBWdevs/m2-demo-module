<?php

namespace Wdevs\InquireManager\Model\ResourceModel\AccountInquire;

/**
 * Class Wdevs\InquireManager\Model\ResourceModel\AccountInquire\Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Wdevs\InquireManager\Model\AccountInquire::class,
            \Wdevs\InquireManager\Model\ResourceModel\AccountInquire::class
        );
        $this->_map['fields']['inquire_id'] = 'main_table.inquire_id';
    }
}
