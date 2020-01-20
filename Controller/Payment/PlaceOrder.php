<?php
/**
 * Nuxpay PlaceOrder controller
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Controller\Payment;

use Nuxpay\Merchant\Model\Payment as NuxpayPayment;
use Magento\Checkout\Model\Session;
use Magento\Backend\Model\Session as BackendSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Model\OrderFactory;

class PlaceOrder extends Action
{
    protected $orderFactory;
    protected $nuxpayPayment;
    protected $checkoutSession;
    protected $backendSession;

    public function __construct(
        Context $context,
        OrderFactory $orderFactory,
        Session $checkoutSession,
        BackendSession $backendSession,
        NuxpayPayment $nuxpayPayment
    ) {
    
        parent::__construct($context);

        $this->orderFactory = $orderFactory;
        $this->checkoutSession = $checkoutSession;
        $this->backendSession = $backendSession;
        $this->nuxpayPayment = $nuxpayPayment;
    }

    /**
     * On calling this, get checkout session order id and save it to backend session
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->checkoutSession->getLastOrderId();

        $order = $this->orderFactory->create()->load($id);

        $this->backendSession->setData('orderId', $id);

        if (!$order->getIncrementId()) {
            $this->getResponse()->setBody(json_encode([
                'status' => false,
                'reason' => 'Order Not Found',
            ]));

            return;
        }

        $this->getResponse()->setBody(json_encode([
            'status' => true
        ]));
    }
}
