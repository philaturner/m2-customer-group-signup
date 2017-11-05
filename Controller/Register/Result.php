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
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $customerGroup;

    protected $userCode;
    protected $userEmail;
    protected $userPwd;
    protected $userFirstName;
    protected $userLastName;

    protected $validCodes;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroup
    ) {
        $this->customerGroup = $customerGroup;
        $this->scopeConfig = $scopeConfig;
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
     * Gets customer ID from module config
     * @return \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    private function getCustomerGroupIDFromConfig()
    {
        return $this->scopeConfig->getValue('config/sleepbenefits/customer_group', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
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

        // Adds customer to specific customer group via admin configuration
        $customer->setGroupId($this->getCustomerGroupIDFromConfig());

        // Preparing data for new customer
        $customer->setEmail($this->userEmail);
        $customer->setFirstname($this->userFirstName);
        $customer->setLastname($this->userLastName);
        $customer->setPassword($this->userPwd);

        // Save data
        try {
            $customer->save();
            $customer->sendNewAccountEmail();
            $this->messageManager->addSuccess(__("Your customer account has been created, please sign in."));
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('A customer may already exist with this email address. Please contact us.'));
        }

    }

    /**
     * Gathers users inputted data and checks with those stored in db
     * @return $this
     */
    public function execute()
    {
        $this->userCode = $this->getRequest()->getParam('unique_code');
        $this->userEmail = $this->getRequest()->getParam('email');
        $this->userPwd = $this->getRequest()->getParam('password');
        $this->userFirstName = $this->getRequest()->getParam('firstname');
        $this->userLastName = $this->getRequest()->getParam('lastname');

        if ($this->isValidCompanyCode()){
            $this->addCustomerToFFACustomerGroup();
        } else {
            $this->messageManager->addNoticeMessage(__('Your code is not valid, please check and try again.'));
        }
        $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
    }
}
