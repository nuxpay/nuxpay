<?php

namespace Nuxpay\Merchant\Model;
use Nuxpay\Merchant\Api\WebserviceInterface;
use Magento\Framework\App\RequestInterface;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;


use Nuxpay\Merchant\Model\Payment as NuxpayPayment;
use Magento\Sales\Model\Order;
use Magento\Framework\App\ObjectManager;
use Nuxpay\Merchant\Model\NuxPayTransaction;
use Nuxpay\Merchant\Model\ResourceModel\NuxPayTransaction\Collection;

class Webservice {

  protected $request;
  protected $scopeConfig;
  protected $order;
  protected $nuxpayPayment;
  protected $transactionCollection;


  public function __construct(
    $webservice,
    RequestInterface $request,
    ScopeConfigInterface $scopeConfig,
    Order $order,
    NuxpayPayment $nuxpayPayment,
    Collection $transactionCollection
  ) {

    $this->scopeConfig = $scopeConfig;
    $this->request = $request;

    $this->order = $order;
    $this->nuxpayPayment = $nuxpayPayment;
    $this->transactionCollection = $transactionCollection;

  }

  public function nuxpayCallback() {

    $secret = $this->request->getParam("secret");

    $arrayJson = json_decode($this->request->getContent());

    $transaction_status = strtolower($arrayJson->params->status ?? "");
    $transaction_address = $arrayJson->params->address ?? "";
    $transaction_id = $arrayJson->params->txID ?? "";

    $amountReceiveUnit = $arrayJson->params->amountReceive ?? "";

    //$amount_receive = $arrayJson->params->creditDetails->amountReceiveDetails->amount ?? "";
    $amount_receive_currency = strtolower($arrayJson->params->creditDetails->amountReceiveDetails->unit ?? "");
    $amount_receive = str_replace(array(" ", strtolower($amount_receive_currency)), "", strtolower($amountReceiveUnit));

    $stored_secret = $this->scopeConfig->getValue('payment/nuxpay_merchant/callback_secret', ScopeInterface::SCOPE_STORE);

    if ($secret != $stored_secret) {
      return "AUTH ERROR";
    } 


    $collection = $this->transactionCollection->addFieldToFilter('transaction_address', $transaction_address);

    foreach ($collection as $item) {
      $orderId = $item->getIdOrder();
      

      if ($transaction_status == 'pending') {
        $this->nuxpayPayment->updateOrderStateAndStatus($orderId, 'pending');
        $item->setTransactionStatus("pending");
      }

      if ($transaction_status == 'failed') {
        $this->nuxpayPayment->updateOrderStateAndStatus($orderId, 'canceled');
        $item->setTransactionStatus("failed");
      }

      if ($transaction_status == 'success') {
        if ($amount_receive >= $item->getAmount() && $amount_receive_currency == strtolower($item->getAmountCurrency()) )  {
          $newInvoiceCreated = $this->nuxpayPayment->createInvoice($orderId);
          $this->nuxpayPayment->updateOrderStateAndStatus($orderId, 'processing');
          $item->setTransactionStatus("success");
        } else {
          $this->nuxpayPayment->updateOrderStateAndStatus($orderId, 'holded');
          $item->setTransactionStatus("holded");
        }
      }

      $item->setTransactionId($transaction_id);
      $item->setAmountReceive($amount_receive);
      $item->setAmountReceiveCurrency($amount_receive_currency);

      $item->save();
    }

    return "OK";

  }

}
