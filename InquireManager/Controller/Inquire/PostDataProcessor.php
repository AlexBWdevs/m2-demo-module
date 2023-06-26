<?php

namespace Wdevs\InquireManager\Controller\Inquire;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Magento\Framework\View\Model\Layout\Update\ValidatorFactory;

class PostDataProcessor
{
    /**
     * @var Date
     */
    protected $dateFilter;

    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @param Date $dateFilter
     * @param ManagerInterface $messageManager
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(
        Date $dateFilter,
        ManagerInterface $messageManager,
        ValidatorFactory $validatorFactory
    ) {
        $this->dateFilter = $dateFilter;
        $this->messageManager = $messageManager;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array $data
     * @return array
     */
    public function filter($data)
    {
        $filterRules = [];

        return (new \Zend_Filter_Input($filterRules, [], $data))->getUnescaped();
    }

    /**
     * Validate post data
     *
     * @param array $data
     * @return bool     Return FALSE if some item is invalid
     */
    public function validate($data)
    {
        $errorNo = true;
        if (!$this->validateRequireEntry($data)) {
            $errorNo = false;
        }
        return $errorNo;
    }

    /**
     * Check if required fields is not empty
     *
     * @param array $data
     * @return bool
     */
    public function validateRequireEntry(array $data)
    {
        $requiredFields = [
            'firstname' => __('Firstname'),
            'lastname' => __('Lastname'),
            'company_name' => __('Company Name'),
            'account_number' => __('Account Number'),
            'city' => __('City'),
            'country' => __('Country'),
            'zip' => __('Zip'),
            'phone' => __('Phone'),
            'email' => __('Email')
        ];
        $errorNo = true;
        foreach ($data as $field => $value) {
            if (in_array($field, array_keys($requiredFields)) && $value == '') {
                $errorNo = false;
                $this->messageManager->addErrorMessage(
                    __('To apply changes you should fill in hidden required "%1" field', $requiredFields[$field])
                );
            }
        }
        return $errorNo;
    }
}
