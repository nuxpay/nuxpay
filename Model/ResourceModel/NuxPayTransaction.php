<?php
/**
 * Nuxpay transaction resource model
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class NuxPayTransaction extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('nuxpay_orders', 'id');
    }
}
