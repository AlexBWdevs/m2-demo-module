<?php
namespace Wdevs\InquireManager\Block\Adminhtml\AccountInquire\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Wdevs\InquireManager\Block\Adminhtml\AccountInquire\Edit\ValidateButton
 */
class ValidateButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        $data = [];
        if ($this->getModelId()) {
            $data = [
                'label' => __('Validate Acc... DEMO'),
                'class' => 'action-secondary',
                'on_click' => "setLocation('" . $this->getValidateUrl() . "')",
                'sort_order' => 100,
            ];
        }
        return $data;
    }
    
    private function getValidateUrl()
    {
        return $this->getUrl('*/*/validateaccdemo', ['inquire_id' => $this->getModelId()]);
    }
}