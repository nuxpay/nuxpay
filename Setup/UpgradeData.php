<?php
/**
 * Nuxpay data upgrade
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\App\ObjectManager;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        
        $objectManager = ObjectManager::getInstance();

        if (version_compare($context->getVersion(), "0.1.1", "<")) {
            if ($installer->getTableRow($installer->getTable('core_config_data'), 'path', 'payment/nuxpay_merchant/title')) {
                $installer->updateTableRow(
                    $installer->getTable('core_config_data'),
                    'path',
                    'payment/nuxpay_merchant/title',
                    'value',
                    'NuxPay'
                );
            }
        }

        if (version_compare($context->getVersion(), "0.1.2", "<")) {
            $status = $objectManager->create('Magento\Sales\Model\Order\Status');
            $status->setData('status', 'pending_nuxpay_confirmation')->setData('label', 'Pending Nuxpay confirmation')->save();
        }
        
        if (version_compare($context->getVersion(), "0.1.3", "<")) {
            $statuses = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Status\Collection');

            foreach ($statuses as $status) {
                if ($status->getStatus() == 'pending_nuxpay_confirmation') {
                    $status->assignState('processing', false, true)->save();
                }
            }
        }
        
        $setup->endSetup();
    }
}
