<?php
namespace Wdevs\InquireManager\Controller\Adminhtml\Inquire;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Wdevs\InquireManager\Api\AccountInquireRepositoryInterface;
use Wdevs\InquireManager\Controller\Adminhtml\AccountInquire;

/**
 * Class Wdevs\InquireManager\Controller\Adminhtml\Inquire\DownloadFile
 */
class DownloadFile extends AccountInquire
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Wdevs_InquireManager::edit';
    
    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var ReadFactory
     */
    protected $readFactory;

    /**
     * @var FileFactory
     */
    protected $fileFactory;

    /**
     * @var Filesystem\Directory\WriteInterface
     */
    protected $_mediaDirectory;

    /**
     * {@inheritDoc}
     * @see \Wdevs\InquireManager\Controller\Adminhtml\AccountInquire::__construct()
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Wdevs\InquireManager\Api\AccountInquireManagementInterface $accountInquireManagement,
        \Wdevs\InquireManager\Api\AccountInquireRepositoryInterface $accountInquireRepository,
        RawFactory $resultRawFactory,
        ReadFactory $readFactory,
        Filesystem $filesystem
    ) {
        parent::__construct(
            $context,
            $accountInquireManagement,
            $accountInquireRepository
        );
        
        $this->fileFactory = $fileFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->readFactory = $readFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
        );
    }

    /**
     * {@inheritDoc}
     * @see \Magento\Framework\App\ActionInterface::execute()
     */
    public function execute()
    {
        $enquireId = $this->getRequest()->getParam('inquire_id');

        /** @var \Wdevs\InquireManager\Api\Data\AccountInquireInterface $inquire */
        $inquire = $this->accountInquireRepository->getById($enquireId);

        $fileName = $inquire->getAttachmentFilename();
        $filePath = $this->_mediaDirectory->getAbsolutePath('inquire_attachments/');

        $readInterface = $this->readFactory->create($filePath);
        $fileContents = $readInterface->readFile($fileName);

        $this->fileFactory->create(
            $fileName,
            $fileContents,
            DirectoryList::MEDIA,
            'application/octet-stream',
            null
        );

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($fileContents);
        return $resultRaw;
    }
}
