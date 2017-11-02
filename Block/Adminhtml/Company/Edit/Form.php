<?php
namespace PhilTurner\GroupPricesInvite\Block\Adminhtml\Company\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('company_form');
        $this->setTitle(__('Company Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \PhilTurner\GroupPricesInvite\Model\Company $model */
        $model = $this->_coreRegistry->registry('grouppricesinvite_company');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('company_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Company Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'invitation_code',
            'text',
            ['name' => 'invitation_code', 'label' => __('Invitation Code'), 'title' => __('Invitation Code'), 'required' => true]
        );

        $fieldset->addField(
            'company_name',
            'text',
            ['name' => 'company_name', 'label' => __('Company Name'), 'title' => __('Company Name'), 'required' => true]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}