<?php

namespace Wdevs\InquireManager\Api\Data;

/**
 * Class Wdevs\InquireManager\Api\Data\AccountInquireInterface
 */
interface AccountInquireInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const INQUIRE_ID = 'inquire_id';
    const FIRSTNAME = 'firstname';
    const LASTNAME = 'lastname';
    const COMPANY_NAME = 'company_name';


    // REMOVED FOR DEMO

    const ATTRIBUTES = [
        self::INQUIRE_ID,
        self::FIRSTNAME,
        self::LASTNAME,
        self::COMPANY_NAME,
        // REMOVED FOR DEMO
    ];
    /**#@-*/

    /**
     * Get inquire_id
     * @return string|null
     */
    public function getId();

    /**
     * Set inquire_id
     * @param $inquire_id
     * @return AccountInquireInterface
     */
    public function setId($inquire_id);

    /**
     * @return mixed
     */
    public function getFirstname();

    /**
     * @param $firstname
     * @return AccountInquireInterface
     */
    public function setFirstname($firstname);

    /**
     * @return mixed
     */
    public function getLastname();

    /**
     * @param $lastname
     * @return AccountInquireInterface
     */
    public function setLastname($lastname);

    /**
     * @return mixed
     */
    public function getCompanyName();

    /**
     * @param $companyName
     * @return AccountInquireInterface
     */
    public function setCompanyName($companyName);

    // REMOVED FOR DEMO

    /**
     * @return mixed
     */
    public function getAttachmentFilename();

    /**
     * @param $filename
     * @return AccountInquireInterface
     */
    public function setAttachmentFilename($filename);

    /**
     * Get Created At
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set Created At
     * @param string $createdAt
     * @return AccountInquireInterface
     */
    public function setCreatedAt($createdAt);
    
    /**
     * Get Status
     * @return int
     */
    public function getStatus();
    
    /**
     * Set status
     * @param int $status
     * @return AccountInquireInterface
     */
    public function setStatus($status);

}
