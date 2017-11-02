<?php
namespace PhilTurner\GroupPricesInvite\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if(!$context->getVersion()) {
            $this->addInviteTable($setup);
        }

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->addInviteTable($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function addInviteTable(SchemaSetupInterface $setup)
    {
        /**
         * Create table 'silentnight_groupprices_invite
         */

        $table = $setup->getConnection()
            ->newTable($setup->getTable('silentnight_groupprices_invite'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'primary' => true, 'unsigned' => true, 'nullable' => false],
                'Primary key'
            )
            ->addColumn(
                'invitation_code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Invitation Code'
            )
            ->addColumn(
                'company_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false],
                'Company Name'
            );

        $setup->getConnection()->createTable($table);
    }

}