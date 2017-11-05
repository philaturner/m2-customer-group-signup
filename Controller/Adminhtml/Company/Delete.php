<?php
namespace PhilTurner\GroupPricesInvite\Controller\Adminhtml\Company;

use Magento\Backend\App\Action;

class Delete extends Action
{
    protected $model;

    /**
     * @param Action\Context $context
     * @param \PhilTurner\GroupPricesInvite\Model\Company $model
     */
    public function __construct(
        Action\Context $context,
        \PhilTurner\GroupPricesInvite\Model\Company $model
    ) {
        parent::__construct($context);
        $this->model = $model;
    }

    //TODO Add isAllowed auth

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->model;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Company deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Company does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}