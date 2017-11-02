<?php
namespace PhilTurner\GroupPricesInvite\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;

class Save extends Action
{
    /**
     * @var \PhilTurner\GroupPricesInvite\Model\Company
     */
    protected $_model;

    /**
     * @param Action\Context $context
     * @param \PhilTurner\GroupPricesInvite\Model\Company $model
     */
    public function __construct(
        Action\Context $context,
        \PhilTurner\GroupPricesInvite\Model\Company $model
    ) {
        parent::__construct($context);
        $this->_model = $model;
    }

    //TODO at ACL verification

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \PhilTurner\GroupPricesInvite\Model\Company $model */
            $model = $this->_model;

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'grouppricesinvite_company_prepare_save',
                ['company' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('Company saved'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the company'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}