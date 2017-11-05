<?php
namespace PhilTurner\GroupPricesInvite\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use PhilTurner\GroupPricesInvite\Model\Company;

class Edit extends Action
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var Company
     */
    protected $_model;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param Company $model
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        Company $model
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_model = $model;
        parent::__construct($context);
    }

    //TODO Add ACL checks (Needs to be created)

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        //$resultPage->setActiveMenu('XXX');
        $resultPage->addBreadcrumb(__('Group Price Invite'), __('Group Price Invite'));
        $resultPage->addBreadcrumb(__('Manage Company'), __('Manage Company'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Company'));
        return $resultPage;
    }

    /**
     * Edit Company
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        $model = $this->_model;

        // If we have got an id, we can edit the company
        // As create forwards here, so if no id then its new
        if ($id) {
            $model->load($id, 'id');
            if (!$model->getId()) {
                $this->messageManager->addError(__('This company does not exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('grouppricesinvite_company', $model);


        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Company') : __('New Company'),
            $id ? __('Edit Company') : __('New Company')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Company'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getDataByKey('company_name') : __('New Company'));

        return $resultPage;
    }
}