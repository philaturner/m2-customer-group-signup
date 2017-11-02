<?php
namespace PhilTurner\GroupPricesInvite\Block\Adminhtml\Company;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }


    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'PhilTurner_GroupPricesInvite';
        $this->_controller = 'adminhtml_company';

        parent::_construct();

        //if ($this->_isAllowedAction('//TODO Add resource ID')) {
            $this->buttonList->update('save', 'label', __('Save Company'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        //} else {
           // $this->buttonList->remove('save');  //remove button if no permissions
        //}

    }

    /**
     * Get header with Company name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('grouppricesinvite_company')->getId()) {
            return __("Edit Company '%1'", $this->escapeHtml($this->_coreRegistry->registry('grouppricesinvite_company')->getName()));
        } else {
            return __('New Company');
        }
    }

    //TODO add _isAllowedAction Resource ID

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('grouppricesinvite/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}