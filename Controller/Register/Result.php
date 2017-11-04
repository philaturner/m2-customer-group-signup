<?php
namespace PhilTurner\GroupPricesInvite\Controller\Register;

use Magento\Framework\App\Action\Context;
use PhilTurner\GroupPricesInvite\Model\ResourceModel\Company\CollectionFactory;

class Result extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    protected $userCode;

    protected $userEmail;

    protected $validCodes;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->storeManager = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Check to see if user code is a valid code
     * @return boolean
     */
    private function isValidCompanyCode()
    {
        //TODO This may be more inefficient that a standard foreach
        $search_array = array_map('strtolower', $this->getValidCodes());
        return in_array(strtolower($this->userCode), $search_array);
    }

    /**
     * Gather all valid invitation codes
     * @return array
     */
    private function getValidCodes()
    {
        $collection = $this->collectionFactory->create();
        return $collection->getColumnValues('invitation_code');
    }

    /**
     * Add customer to selected customer group
     */
    private function addCustomerToFFACustomerGroup()
    {
        // Get Website ID
        $websiteId  = $this->storeManager->getWebsite()->getWebsiteId();

        // Instantiate object
        $customer = $this->customerFactory->create();
        $customer->setWebsiteId($websiteId);

        // Adds customer to specific customer group //TODO pull in group from config
        $customer->setGroupId(4);

        // Preparing data for new customer
        $customer->setEmail($this->userEmail);
        $customer->setFirstname("Test");
        $customer->setLastname("Person");
        $customer->setPassword("password");

        // Save data
        try {
            $customer->save();
            $customer->sendNewAccountEmail();
            $this->messageManager->addSuccess(__("Your customer account has been created, you'll receive a welcome Email."));
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('A customer may already exist with this email address. Please contact us.'));
        }

    }

    /**
     * Gathers users inputted code and checks with those stored in db
     * @return $this
     */
    public function execute()
    {
        $this->userCode = $this->getRequest()->getParam('unique_code');
        $this->userEmail = $this->getRequest()->getParam('email');

        if ($this->isValidCompanyCode()){
            $this->addCustomerToFFACustomerGroup();
        } else {
            $this->messageManager->addNoticeMessage(__('Your code is not valid, please check and try again.'));
        }
        $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
    }
}
