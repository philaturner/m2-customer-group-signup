<?php
namespace PhilTurner\GroupPricesInvite\Controller\Register;

class Result extends \Magento\Framework\App\Action\Action
{
    protected $validCodes = [
        '1111',
        '2222',
        '3333',
    ];

    protected $userCode;

    private function isValidCompanyCode()
    {
        foreach ($this->validCodes as $codes) {
            if ($codes == $this->userCode){
                return true;
            }
        }
        return false;
    }

    public function execute()
    {

        $this->userCode = $this->getRequest()->getParam('unique_code');

        if ($this->isValidCompanyCode($this->userCode)){
            echo ("Code is a valid one");
        } else {
            echo ("You did not enter a valid code");
        }

        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();

    }
}
