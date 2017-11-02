<?php
namespace PhilTurner\GroupPricesInvite\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

/**
 * @codeCoverageIgnore
 */
class Uninstall implements UninstallInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $setup->getConnection()->dropTable($setup->getTable('silentnight_groupprices_invite'));

        $setup->endSetup();
    }

}