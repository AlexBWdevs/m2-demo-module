<?php
namespace Wdevs\InquireManager\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Wdevs\InquireManager\Model\OptionSource\RequestStatus;

/**
 * Class Wdevs\InquireManager\Ui\Component\Listing\Column\CustomerStatus
 */
class CustomerStatus extends Column
{
    /**
     * {@inheritDoc}
     * @see \Magento\Ui\Component\AbstractComponent::prepareDataSource()
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as $key => &$item) {
                $item['status_id'] = $item[$this->getData('name')];
                $item[$this->getData('name')] = $this->prepareStatus($item[$this->getData('name')]);
            }
        }
        return $dataSource;
    }
    
    /**
     * @param string $status
     * @return string
     */
    private function prepareStatus($status)
    {
        switch ($status) {
            case RequestStatus::ACCOUNT_REQUESTED:
                return '<span class="request-status-requested"><span>' . __('Account Requested') . '</span></span>';
                break;
                // code removed from demo
            case RequestStatus::ACCOUNT_CREATED:
                return '<span class="request-status-created"><span>' . __('Account Created') . '</span></span>';
                break;
        }
        
        return '';
    }
}
