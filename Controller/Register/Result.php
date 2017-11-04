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

    protected $userCode;

    protected $validCodes;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     *
     */
    private function isValidCompanyCode()
    {
        foreach ($this->getValidCodes() as $codes) {
            if (strtolower($codes) == strtolower($this->userCode)){
                return true;
            }
        }
        return false;
    }

    /**
     *
     */
    private function getValidCodes()
    {
        $collection = $this->collectionFactory->create();
        $this->validCodes = $collection->getColumnValues('invitation_code');
        return $this->validCodes;
    }

    /**
     *
     */
    public function execute()
    {
        $this->userCode = $this->getRequest()->getParam('unique_code');

        if ($this->isValidCompanyCode()){
            $this->messageManager->addSuccess(__('Your invitation has been sent.'));
            //TODO Add code to send invitation
        } else {
            $this->messageManager->addNoticeMessage(__('Your code is not valid, please check and try again.'));
        }
        $this->getResponse()->setRedirect($this->_redirect->getRedirectUrl());
    }
}
