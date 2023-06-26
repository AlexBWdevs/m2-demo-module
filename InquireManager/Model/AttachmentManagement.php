<?php
namespace Wdevs\InquireManager\Model;

use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Class Wdevs\InquireManager\Model\AttachmentManagement
 */
class AttachmentManagement
{
    const ATTACHMENT_DIR = 'inquire_attachments/';
    
    private $allowedExtensions = ['jpg', 'pdf', 'doc', 'png', 'zip', 'docx'];
    
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;
    
    /**
     * @var Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;
    
    /**
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }
    
    /**
     * @param string $fileId
     * @return boolean|array
     */
    public function uploadAttachment($fileId)
    {
        $target = $this->mediaDirectory->getAbsolutePath(self::ATTACHMENT_DIR);
        
        /**
         * @var \Magento\MediaStorage\Model\File\Uploader $uploader
         */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->allowedExtensions);
        $uploader->setFilesDispersion(false);
        $uploader->setFilenamesCaseSensitivity(false);
        $uploader->setAllowRenameFiles(true);
        
        try {
            $result = $uploader->save($target);
        } catch (\Exception $e) {
            $result = false;
        }
        
        return $result;
    }
}