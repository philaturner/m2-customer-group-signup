<?php
namespace PhilTurner\GroupPricesInvite\Model\Import;

use \Magento\ImportExport\Model\Import\Entity\AbstractEntity;

class Company extends AbstractEntity
{

    /**
     * Import data rows.
     *
     * @return boolean
     */
    protected function _importData()
    {
        // TODO: Implement _importData() method.
    }

    /**
     * EAV entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        // TODO: Implement getEntityTypeCode() method.
    }

    /**
     * Validate data row.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return boolean
     */
    public function validateRow(array $rowData, $rowNum)
    {
        // TODO: Implement validateRow() method.
    }
}