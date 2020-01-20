<?php
/**
 * Nuxpay block for paying
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Block;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Backend\Model\Session;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;
use Nuxpay\Merchant\Model\NuxPayTransaction;
use Nuxpay\Merchant\Model\ResourceModel\NuxPayTransaction\Collection;

class NuxPay extends Template
{
    protected $backendSession;
    protected $transactionCollection;

    // If debug mode is enabled, reuse addresses
    const DEBUG = false;

    const BASE_URL = 'https://www.nuxpay.com';
    // const PRICE_URL = 'https://www.nuxpay.com/api/price';
    // const NEW_ADDRESS_URL = 'https://www.nuxpay.com/api/new_address';

    public function __construct(
        Context $context,
        Session $backendSession,
        OrderRepositoryInterface $orderRepository,
        ScopeConfigInterface $scopeConfig,
        Collection $transactionCollection
    ) {
        parent::__construct($context);
        $this->backendSession = $backendSession;
        $this->orderRepository = $orderRepository;
        $this->scopeConfig = $scopeConfig;
        $this->transactionCollection = $transactionCollection;
    }

    /**
     * @return Current order id from backend session
     */
    public function getOrderId()
    {
        return $this->backendSession->getData('orderId', true);
    }

    /**
     * @param int $orderId
     * @return Order by id
     */
    public function getOrderById($orderId)
    {
        return $this->orderRepository->get($orderId);
    }

    /**
     * @return Convert currency to fiat currency
     */
    public function getOrderPriceInFiat($orderId)
    {
        //$orderId = $this->getOrderId();
        //echo $orderId."<<<";
        //$orderId = "60";
        $order = $this->getOrderById($orderId);
        return $order->getGrandTotal();
    }

    /**
     * @return Currency code from store
     */
    public function getCurrencyCode()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    /**
     * Create new order into database
     */
    public function createNuxPayTransaction($order_id, $transaction_token, $transaction_reference_id, $transaction_address, $transaction_status, $crypto_amount, $crypto_unit)
    {
        $objectManager = ObjectManager::getInstance();
        $nuxPayTransaction = $objectManager->create('Nuxpay\Merchant\Model\NuxPayTransaction');

        $orderTimestamp = time();
        $this->backendSession->setData('orderTimestamp', $orderTimestamp);


        $nuxPayTransaction->setIdOrder($order_id);
        $nuxPayTransaction->setTransactionToken($transaction_token);

        $nuxPayTransaction->setTransactionReferenceId($transaction_reference_id);
        $nuxPayTransaction->setTransactionAddress($transaction_address);

        $nuxPayTransaction->setTransactionStatus($transaction_status);
        $nuxPayTransaction->setAmount($crypto_amount);
        $nuxPayTransaction->setAmountCurrency($crypto_unit);

        $nuxPayTransaction->setCreatedTimestamp($orderTimestamp);
        $nuxPayTransaction->setUpdatedTimestamp($orderTimestamp);

        $nuxPayTransaction->save();

    }

    /**
     * @return Secret from core_config
     */
    public function getSecret()
    {
        return $this->scopeConfig->getValue('payment/nuxpay_merchant/callback_secret', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return WalletTypes from Nuxpay API
     */
    public function getWalletTypes()
    {
        $apiKey = $this->scopeConfig->getValue('payment/nuxpay_merchant/app_key', ScopeInterface::SCOPE_STORE);
        $businessID = $this->scopeConfig->getValue('payment/nuxpay_merchant/business_id', ScopeInterface::SCOPE_STORE);

        $params = [
            "business_id" => $businessID,
            "api_key" => $apiKey
        ];

        $json_params = json_encode($params);
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, "https://www.thenux.com:5281/business/pg/destination/address/list/get");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        $contents = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close ($ch);

        $arrContents = json_decode($contents, true);

        if($arrContents["code"] == 1) {
            $support_pg_currency = $arrContents["data"];
        } else {
            $support_pg_currency = array();
        }

        return $support_pg_currency;
    }

    /**
     * @return conversionRate from Nuxpay API
     */
    public function getConversionRate($params){

        $apiKey = $this->scopeConfig->getValue('payment/nuxpay_merchant/app_key', ScopeInterface::SCOPE_STORE);
        $businessID = $this->scopeConfig->getValue('payment/nuxpay_merchant/business_id', ScopeInterface::SCOPE_STORE);
        $orderCurrency = $params['orderCurrency'];
        $selected_crypto_type = strtolower($params['selected_crypto_type']);
        $orderGrandTotal = $params['orderGrandTotal'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.thenux.com:5281/crypto/conversion/rate/get");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("business_id" => $businessID, "api_key" => $apiKey, "fiat_currency_id" => strtolower($orderCurrency), "wallet_type" => $selected_crypto_type, "amount" => $orderGrandTotal)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        $contents = curl_exec($ch);
        curl_close ($ch);

        $arrContents = json_decode($contents, true);

        if($arrContents["code"] == 1) {
            $crypto_converted_amount = $arrContents["data"]["crypto_converted_amount"];
            $currency_unit = $arrContents["data"]["currency_unit"];
        } else {
            $crypto_converted_amount = "";
            $currency_unit = "";
        }

        $return['fiat_total'] = $orderGrandTotal;
        $return['fiat_currency'] = $orderCurrency;
        $return['crypto_total'] = $crypto_converted_amount;
        $return['crypto_currency'] = $currency_unit;

        return $return;
    }

    /**
     * @return transactionToken from Nuxpay API
     */
    public function requestTransaction($checkout_currency, $checkout_amount, $orderId, $crypto_unit) {

        $apiKey = $this->scopeConfig->getValue('payment/nuxpay_merchant/app_key', ScopeInterface::SCOPE_STORE);
        $businessID = $this->scopeConfig->getValue('payment/nuxpay_merchant/business_id', ScopeInterface::SCOPE_STORE);
        $respondURL = $this->scopeConfig->getValue('payment/nuxpay_merchant/respond_url', ScopeInterface::SCOPE_STORE);


        $params = array("business_id"=>$businessID, "api_key"=>$apiKey, "currency"=>$checkout_currency, "amount"=>$checkout_amount, "reference_id"=>$orderId,"redirect_url"=>$respondURL);
        

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.thenux.com:5281/xun/payment_gateway/merchant/transaction/request");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $params ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        $contents = curl_exec($ch);
        curl_close ($ch);

        $arrContents = json_decode($contents, true);

        if($arrContents["code"] == 1) {
            $transaction_token = $arrContents["data"]["transaction_token"];
            $transaction_reference_id = $arrContents["data"]["reference_id"];
            $transaction_address = $arrContents["data"]["address"];


            $this->createNuxPayTransaction($orderId, $transaction_token, $transaction_reference_id, $transaction_address, "pending", $checkout_amount, $crypto_unit);


            return $transaction_token;

        } else {
            return "";

        }

    }

}
