<?php
/**
 * Nuxpay Respond controller
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Controller\Payment;

use Nuxpay\Merchant\Model\Payment as NuxpayPayment;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Model\Order;
use Magento\Framework\App\ObjectManager;
use Nuxpay\Merchant\Model\NuxPayTransaction;
use Nuxpay\Merchant\Model\ResourceModel\NuxPayTransaction\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey;

class Respond extends Action {
    protected $order;
    protected $nuxpayPayment;
    protected $transactionCollection;
    protected $scopeConfig;
    protected $request;
    protected $formKey;

    public function __construct(
        Context $context,
        Order $order,
        NuxpayPayment $nuxpayPayment,
        ScopeConfigInterface $scopeConfig,
        Collection $transactionCollection,
        Http $request,
        FormKey $formKey ) {

        parent::__construct($context);

        $this->order = $order;
        $this->nuxpayPayment = $nuxpayPayment;
        $this->scopeConfig = $scopeConfig;
        $this->transactionCollection = $transactionCollection;
        $this->request = $request;
        $this->formKey = $formKey;
        $this->request->setParam('form_key', $this->formKey->getFormKey());

    }

    /**
     * When this callback is called, get parameters from GET query and decide
     * correct action using them
     *
     * If status = 0, set payment pending
     * If status = 2 and paid amoun >= order total, set order processing and create invoice
     * If status = 2 and paid amoun < order total, set payment on hold
     *
     * @return void
     */

    public function execute() {

        $status = strtolower($this->request->getParam("status"));
        $transaction_token = $this->request->getParam("transaction_token");


        $collection = $this->transactionCollection->addFieldToFilter('transaction_token', $transaction_token);

        foreach ($collection as $item) {
          $item->setTransactionStatus($status);
          $item->save();
        }

        if($status=="success") {
            echo "<script>window.location.href='".$this->_url->getUrl('checkout/onepage/success')."';</script>";
        } else {
            echo "<script>window.location.href='".$this->_url->getUrl('checkout/onepage/failure')."';</script>";
        }
        
  }
}
