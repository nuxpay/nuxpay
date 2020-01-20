/**
 * Nuxpay payment method model
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'nuxpay_merchant',
                component: 'Nuxpay_Merchant/js/view/payment/method-renderer/nuxpay-method'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
