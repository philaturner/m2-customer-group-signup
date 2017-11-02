<?php
namespace PhilTurner\GroupPricesInvite\Controller\Adminhtml\Company;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        //$resultPage->setActiveMenu('XXX');
        $resultPage->addBreadcrumb(__('Group Price Invite'), __('Group Price Invite'));
        $resultPage->addBreadcrumb(__('Manage Company'), __('Manage Company'));
        $resultPage->getConfig()->getTitle()->prepend(__('Group Price - Company Grid'));
        return $resultPage;
    }
}