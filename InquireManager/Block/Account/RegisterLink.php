<?php

namespace Wdevs\InquireManager\Block\Account;

use Magento\Customer\Model\Registration;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\UrlInterface;

class RegisterLink extends \Magento\Framework\View\Element\Html\Link
{

    /**
     * Customer session
     *
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var Registration
     */
    protected $_registration;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param Context $context
     * @param HttpContext $httpContext
     * @param Registration $registration
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        HttpContext $httpContext,
        Registration $registration,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
        $this->_registration = $registration;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->urlBuilder->getUrl('request-account');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        if ($this->httpContext->getValue(CustomerContext::CONTEXT_AUTH)
        ) {
            return '';
        }
        return parent::_toHtml();
    }
}
