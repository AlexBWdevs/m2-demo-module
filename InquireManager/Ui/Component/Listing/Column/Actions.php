<?php

namespace Wdevs\InquireManager\Ui\Component\Listing\Column;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Wdevs\InquireManager\Model\OptionSource\RequestStatus;

/**
 * Class Actions
 */
class Actions extends Column
{
    /** Url path */
    const ACCOUNT_MANAGER_INQUIRE_PATH_VIEW = 'inquiremanager/inquire/view';
    const ACCOUNT_MANAGER_INQUIRE_PATH_EDIT = 'inquiremanager/inquire/edit';
    const ACCOUNT_MANAGER_INQUIRE_PATH_DELETE = 'inquiremanager/inquire/delete';
    const ACCOUNT_MANAGER_INQUIRE_PATH_CREATE_CUSTOMER = 'inquiremanager/inquire/createcustomer';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;
    
    /**
     * @var RequestStatus
     */
    private $requestStatus;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        RequestStatus $requestStatus,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->requestStatus = $requestStatus;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['inquire_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::ACCOUNT_MANAGER_INQUIRE_PATH_EDIT,
                            [
                                'inquire_id' => $item['inquire_id']
                            ]
                        ),
                        'label' => __('View Request')
                    ];
                    $email = $this->getEscaper()->escapeHtml($item['email']);
                    $status = $item['status_id'] ?? null;
                    if (in_array($status, $this->requestStatus->getAlowedCreateCustomerStatus())) {
                        $item[$name]['create'] = [
                            'href' => $this->urlBuilder->getUrl(
                                self::ACCOUNT_MANAGER_INQUIRE_PATH_CREATE_CUSTOMER,
                                [
                                    'inquire_id' => $item['inquire_id']
                                ]
                                ),
                            'label' => __('Create Customer'),
                            'confirm' => [
                                'title' => __('Create Customer %1', $email),
                                'message' => __('Are you sure you want to create customer for a %1?', $email),
                                '__disableTmpl' => true,
                            ],
                            'post' => true,
                        ];
                    }
              
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::ACCOUNT_MANAGER_INQUIRE_PATH_DELETE,
                            [
                                'inquire_id' => $item['inquire_id']
                            ]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $email),
                            'message' => __('Are you sure you want to delete a %1 record?', $email),
                            '__disableTmpl' => true,
                        ],
                        'post' => true,
                    ];

                }
            }
        }

        return $dataSource;
    }
    
    /**
     * Get instance of escaper
     *
     * @return Escaper
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}
