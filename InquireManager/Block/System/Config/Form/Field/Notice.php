<?php
/**
 * Renderer for sub-heading in fieldset
 */
namespace Wdevs\InquireManager\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Notice extends \Magento\Backend\Block\AbstractBlock implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Render element html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return sprintf(
            '<tr class="system-fieldset-notice" id="row_%s"><td colspan="5"><span id="%s">%s</span></td></tr>',
            $element->getHtmlId(),
            $element->getHtmlId(),
            $element->getLabel()
        );
    }
}
