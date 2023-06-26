<?php

namespace Wdevs\InquireManager\Ui\Component\Form\Inquire;

use Magento\Framework\Filesystem;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;

class FileDownloadUrl extends \Magento\Ui\Component\Form\Field
{
    const DOWNLOAD_ATTACHMENT_URL = 'inquiremanager/inquire/downloadFile';

    /**
     * @var Filesystem\Directory\WriteInterface
     */
    protected $_mediaDirectory;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Filesystem $filesystem
     * @param UiComponentInterface[] $components
     * @param array $data
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Filesystem $filesystem,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }

    /**
     * {@inheritdoc}
     */
    public function prepareDataSource(array $dataSource)
    {
        parent::prepareDataSource($dataSource);

        $fileName = $dataSource['data']['attachment_filename'];
        if (empty($fileName) || !isset($dataSource['data']['inquire_id'])) {
            return $dataSource;
        }

        $url = $this->getUrl(self::DOWNLOAD_ATTACHMENT_URL, ['inquire_id' => $dataSource['data']['inquire_id']]);

        $dataSource['data']['attachment_download_url'] = '<a href="' . $url . '">' . __('Download File: %1', $fileName) . '</a>';
        return $dataSource;
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    private function getUrl($route = '', $params = [])
    {
        return $this->getContext()->getUrl($route, $params);
    }
}
