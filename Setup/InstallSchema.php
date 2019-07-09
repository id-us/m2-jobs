<?php

namespace Idus\Jobs\Setup;
/**
 * @author Gilad Hatav Idus <gilad@idus.co.il>
 */
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
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
        $installer = $setup;

        $installer->startSetup();
        if (!$installer->tableExists('idus_jobs')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('idus_jobs')
            )->addColumn(
                    'job_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true],
                    'jobs ID'
                )->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Title'
                )->addColumn(
                    'code',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Code'
                )->addColumn(
                    'store',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Store'
                )->addColumn(
                    'emails',
                    Table::TYPE_TEXT,
                    100,
                    ['nullable' => false],
                    'Emails'
                )->addColumn(
                    'content',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => false],
                    'Content'
                )->addColumn(
                    'short_content',
                    Table::TYPE_TEXT,
                    '1M',
                    ['nullable' => false],
                    'Short Content'
                )->addColumn(
                    'is_active',
                    Table::TYPE_SMALLINT,
                    null,
                    [],
                    'Active Status'
                )->addIndex(
                    $installer->getIdxName(
                        $installer->getTable('idus_jobs'),
                        ['code'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['code'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )->setComment(
                    'Jobs Table'
                );
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();

    }
}