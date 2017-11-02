<?php
namespace PhilTurner\GroupPricesInvite\Model\ResourceModel\Company;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = \PhilTurner\GroupPricesInvite\Model\Company::COMPANY_ID;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'PhilTurner\GroupPricesInvite\Model\Company',
            'PhilTurner\GroupPricesInvite\ResourceModel\Company'
        );
    }
}