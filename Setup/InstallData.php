<?php
/**
 * Nuxpay data installation
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $callback_secret = sha1(openssl_random_pseudo_bytes(20));

        $data_callback_secret = [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'payment/nuxpay_merchant/callback_secret',
            'value' => $callback_secret
        ];
        $setup->getConnection()
            ->insertOnDuplicate($setup->getTable('core_config_data'), $data_callback_secret, ['value']);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $base_url = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);

        $data_title = [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'payment/nuxpay_merchant/title',
            'value' => 'NuxPay'
        ];
        $setup->getConnection()
            ->insertOnDuplicate($setup->getTable('core_config_data'), $data_title, ['value']);

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
        $base_url = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);

        $data_callback_url = [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'payment/nuxpay_merchant/callback_url',
            'value' => $base_url . 'rest/V1/nuxpay/callback?secret=' . $callback_secret . '&json_header=1'
        ];
        $setup->getConnection()
            ->insertOnDuplicate($setup->getTable('core_config_data'), $data_callback_url, ['value']);

        $data_respond_url = [
            'scope' => 'default',
            'scope_id' => 0,
            'path' => 'payment/nuxpay_merchant/respond_url',
            'value' => $base_url . 'nuxpay/payment/respond'
        ];
        $setup->getConnection()
            ->insertOnDuplicate($setup->getTable('core_config_data'), $data_respond_url, ['value']);
                    
    }
}
