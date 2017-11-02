<?php
namespace PhilTurner\GroupPricesInvite\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Company extends AbstractDb
{

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        // Table Name and Primary Key column
        $this->_init('silentnight_groupprices_invite', 'id');
    }

}