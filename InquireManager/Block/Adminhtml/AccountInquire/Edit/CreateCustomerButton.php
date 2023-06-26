<?php
namespace Wdevs\InquireManager\Block\Adminhtml\AccountInquire\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Wdevs\InquireManager\Api\AccountInquireRepositoryInterface;
use Wdevs\InquireManager\Model\OptionSource\RequestStatus;

/**
 * Class Wdevs\InquireManager\Block\Adminhtml\AccountInquire\Edit\CreateCustomerButton
 */
class CreateCustomerButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var AccountInquireRepositoryInterface
     */
    private $accountInquireRepository;
    
    /**
     * @var RequestStatus
     */
    private $requestStatus;
    
    /**
     * @param Context $context
     * @param AccountInquireRepositoryInterface $accountInquireRepository
     * @param RequestStatus $requestStatus
     */
    public function __construct(
        Context $context,
        AccountInquireRepositoryInterface $accountInquireRepository,
        RequestStatus $requestStatus
    ) {
        parent::__construct($context);
        $this->accountInquireRepository = $accountInquireRepository;
        $this->requestStatus = $requestStatus;
    }
    
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getModelId() && $this->isAllowedCreateCustomer()) {
            $data = [
                'label' => __('Create Customer'),
                'class' => 'save primary',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Please save yor changes before continue! \nAre you sure you want to create customer #%1 ? ',
                        $this->getModelId()
                    ) . '\', \'' . $this->getCreateCustomerUrl() . '\')',
                'sort_order' => 100,
            ];
        }
        return $data;
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getCreateCustomerUrl()
    {
        return $this->getUrl('*/*/createCustomer', ['inquire_id' => $this->getModelId()]);
    }
    
    /**
     * @return boolean
     */
    private function isAllowedCreateCustomer()
    {
        $accountInquire = $this->accountInquireRepository->getById($this->getModelId());
        
        return in_array($accountInquire->getStatus(), $this->requestStatus->getAlowedCreateCustomerStatus());
    }
}
