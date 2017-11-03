<?php
namespace PhilTurner\GroupPricesInvite\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

class Register extends Template implements BlockInterface
{

    protected $request;

    public function __construct(
        Context $context,
        array $data = []
    )
    {
        $this->setTemplate('widget/form.phtml');
        parent::__construct($context,  $data);
    }


    /**
     * Returns action url for register form
     * @param string
     * @return string
     */
    public function getFormAction()
    {

    }

}
