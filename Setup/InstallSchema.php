<?php
namespace PhilTurner\GroupPricesInvite\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

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

        $setup->endSetup();
    }
}
