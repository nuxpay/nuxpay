<?php
/**
 * Nuxpay schema installation
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $tableName = $installer->getTable('nuxpay_orders');

        // Check if the table already exists
        if ($installer->getConnection()->isTableExists($tableName) != true) {
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'order_id',
                    Table::TYPE_TEXT,
                    null,
		    ['nullable' => false],
                    'Order ID'
	   	 )
		 ->addColumn(
                    'transaction_token',
                    Table::TYPE_TEXT,
                    null,
                    [ 'nullable' => false ],
                    'Transaction Token'
	    	)
	    	->addColumn(
                    'transaction_reference_id',
                    Table::TYPE_TEXT,
                    null,
                    [ 'nullable' => false ],
                    'Transaction Reference ID'
                )
		->addColumn(
                    'transaction_address',
                    Table::TYPE_TEXT,
                    null,
                    [ 'nullable' => false ],
                    'Transaction Address'
	   	)
		->addColumn(
                    'transaction_id',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false ],
                    'Transaction ID'
                )
                ->addColumn(
                    'transaction_status',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false ],
                    'Transaction Status'
            	)
		->addColumn(
                    'amount',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false ],
                    'Amount in satoshi'
	    	)
	    	->addColumn(
                    'amount_currency',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false ],
                    'Amount currency'
                )
                ->addColumn(
                    'amount_receive',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false ],
                    'Amount receive in satoshi'
                )
                ->addColumn(
                    'amount_receive_currency',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false ],
                    'Amount receive currency'
                )
                ->addColumn(
                    'created_timestamp',
                    Table::TYPE_INTEGER,
                    null,
                    [ 'nullable' => false ],
                    'Created timestamp'
	    	)
	        ->addColumn(
                    'updated_timestamp',
                    Table::TYPE_INTEGER,
                    null,
                    [ 'nullable' => false ],
                    'Updated timestamp'
	    	)
                ->setComment('Nuxpay Order Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');

            $installer->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}
