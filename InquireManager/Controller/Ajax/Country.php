<?php
namespace Wdevs\InquireManager\Controller\Ajax;

use Magento\Directory\Block\Data as Directory;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\CountryFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Escaper;

class Country extends \Magento\Framework\App\Action\Action
{
    /**
     * Country region collections
     *
     * Structure:
     * array(
     *      [$countryId] => \Magento\Framework\Data\Collection\AbstractDb
     * )
     *
     * @var array
     */
    private static $regionCollections;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var CountryFactory
     */
    protected $countryFactory;
    /**
     * @var RegionFactory
     */
    protected $regionFactory;

    /**
     * @var Directory
     */
    protected $directoryBlock;

    /**
     * @var DirectoryHelper
     */
    protected $directoryHelper;

    /**
     * @var Escaper
     */
    protected $_escaper;

    /**
     * Country constructor.
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param CountryFactory $countryFactory
     * @param RegionFactory $regionFactory
     * @param Directory $directory
     * @param DirectoryHelper $directoryHelper
     * @param Escaper $_escaper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CountryFactory $countryFactory,
        RegionFactory $regionFactory,
        Directory $directory,
        DirectoryHelper $directoryHelper,
        Escaper $_escaper
    ) {
        $this->countryFactory = $countryFactory;
        $this->regionFactory = $regionFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->directoryBlock = $directory;
        $this->directoryHelper = $directoryHelper;
        $this->_escaper = $_escaper;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();

        $result = $this->resultJsonFactory->create();
        if (!$this->getRequest()->getParam('country_id')) {
            return $result->setData(['success' => false]);
        }

        $html = $this->getRegionHtml($this->getRequest()->getParam('country_id'));

        return $result->setData(['success' => true,'value'=>$html]);
    }

    protected function getRegionHtml($countryId)
    {
        $isRegionRequired = $this->directoryHelper->isRegionRequired($countryId);

        if (!isset(self::$regionCollections[$countryId])) {
            self::$regionCollections[$countryId] = $this->countryFactory->create()->setId(
                $countryId
            )->getLoadedRegionCollection()->toOptionArray();
        }
        $regionCollection = self::$regionCollections[$countryId];

        $requiredClass = '';
        if ($isRegionRequired) {
            $requiredClass = 'required-entry ';
        }
        if ($regionCollection && count($regionCollection) > 0) {
            $regionId = '';
            $html = '<div class="field"><select name="region" id="state" class="' . $requiredClass . 'validate-state" title="' . __('State/Province') . '">';
            foreach ($regionCollection as $region) {
                $selected = $regionId == $region['value'] ? ' selected="selected"' : '';
                $regionVal = 0 == $region['value'] ? '' : (int)$region['value'];
                $html .= '<option value="' . $regionVal . '"' . $selected . '>'
                    . $this->_escaper->escapeHtml(__($region['label'])) . '</option>';
            }
            $html .= '</select></div>';
        } else {
            $html = '<input class="' . $requiredClass . ' field" type="text" name="state" id="state" value="" placeholder="' . __('State') . '"/>';
        }
        return $html;
    }
}
