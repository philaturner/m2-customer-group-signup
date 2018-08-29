<?php
namespace PhilTurner\GroupPricesInvite\Model\Import;

use PhilTurner\GroupPricesInvite\Model\Import\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\App\ResourceConnection;

class Company extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{

    CONST TABLE_ENTITY = 'silentnight_groupprices_invite';

    CONST INVITATION_CODE = 'invitation_code';
    CONST COMPANY_NAME = 'company_name';

    protected $_messageTemplates = [
        ValidatorInterface::ERROR_NAME_IS_EMPTY => 'Company Name is empty',
        ValidatorInterface::ERROR_CODE_IS_EMPTY => 'Invitation Code is empty',
    ];

    protected $_permanentAttributes = (self::COMPANY_NAME);

    protected $needColumnCheck = true;

    protected $validColumnNames = [
        self::INVITATION_CODE,
        self::COMPANY_NAME,
    ];

    protected $logInHistory;
    protected $_validators = [];
    protected $_connection;
    protected $_resource;


    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
    }


    public function getEntityTypeCode()
    {
        return 'company_import';
    }


    public function validateRow(array $rowData, $rowNum)
    {
        $title = false;
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;
        // BEHAVIOR_DELETE use specific validation logic
        // if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()) {
        if (!isset($rowData[self::INVITATION_CODE]) || empty($rowData[self::INVITATION_CODE])) {
            $this->addRowError(ValidatorInterface::ERROR_CODE_IS_EMPTY, $rowNum);
            return false;
        }
        if (!isset($rowData[self::COMPANY_NAME]) || empty($rowData[self::COMPANY_NAME])) {
            $this->addRowError(ValidatorInterface::ERROR_NAME_IS_EMPTY, $rowNum);
            return false;
        }
        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    //define what to do!!!!!!!!!!!
    protected function _importData()
    {
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_DELETE == $this->getBehavior()){
            $this->deleteEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $this->getBehavior()){
            $this->replaceEntity();
        } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $this->getBehavior()){
            $this->saveEntity();
        }
        return true;
    }

    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    public function replaceEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }

    public function deleteEntity()
    {
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);
                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowTitle = $rowData[self::INVITATION_CODE];
                    $listTitle[] = $rowTitle;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }
        if ($listTitle) {
            $this->deleteEntityFinish(array_unique($listTitle),self::TABLE_ENTITY);
        }
        return $this;
    }

    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        $listTitle = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];
            foreach ($bunch as $rowNum => $rowData) {
                if (!$this->validateRow($rowData, $rowNum)) {
                    $this->addRowError(ValidatorInterface::ERROR_CODE_IS_EMPTY, $rowNum);
                    continue;
                }
                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                    continue;
                }
                $rowTitle= $rowData[self::INVITATION_CODE];
                $listTitle[] = $rowTitle;
                $entityList[$rowTitle][] = [
                    self::INVITATION_CODE => $rowData[self::INVITATION_CODE],
                    self::COMPANY_NAME => $rowData[self::COMPANY_NAME],
                ];
            }
            if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {
                if ($listTitle) {
                    if ($this->deleteEntityFinish(array_unique(  $listTitle), self::TABLE_ENTITY)) {
                        $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
                    }
                }
            } elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
                $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
            }
        }
        return $this;
    }

    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $id => $entityRows) {
                foreach ($entityRows as $row) {
                    $entityIn[] = $row;
                }
            }
            if ($entityIn) {
                $this->_connection->insertOnDuplicate($tableName, $entityIn,[
                    self::INVITATION_CODE,
                    self::COMPANY_NAME
                ]);
            }
        }
        return $this;
    }

    protected function deleteEntityFinish(array $listTitle, $table)
    {
        if ($table && $listTitle) {
            try {
                $this->countItemsDeleted += $this->_connection->delete(
                    $this->_connection->getTableName($table),
                    $this->_connection->quoteInto('invitation_code IN (?)', $listTitle)
                );
                return true;
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }


}
