<?php
/**
 * Nuxpay disabled form for admin view
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Disable extends \Magento\Config\Block\System\Config\Form\Field
{

    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setData('readonly', 1);
        $element->setData('placeholder', 'Please refresh magento cache, and come back to this page');
        return $element->getElementHtml();
    }
}
