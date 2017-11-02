<?php
namespace PhilTurner\GroupPricesInvite\Block\Widget;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

class Register extends Template implements BlockInterface
{

    public function __construct(
        Context $context,
        array $data = []
    )
    {
        $this->setTemplate('widget/form.phtml');
        parent::__construct($context, $data);
    }

    /**
     * Returns action url for contact form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('contact/index', ['_secure' => true]);
    }
}
