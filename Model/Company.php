<?php
namespace PhilTurner\GroupPricesInvite\Model;

use \Magento\Framework\Model\AbstractModel;

class Company extends AbstractModel
{
    const COMPANY_ID = 'id'; // We define the id fieldname

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'company';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'company';

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = self::COMPANY_ID;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('PhilTurner\GroupPricesInvite\Model\ResourceModel\Company');
    }
}