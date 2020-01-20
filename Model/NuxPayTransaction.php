<?php
/**
 * Nuxpay transaction model
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Model;

use \Magento\Framework\Model\AbstractModel;
// use \Magento\Framework\DataObject\IdentityInterface;
use \Nuxpay\Merchant\Api\Data\NuxPayTransactionInterface;

class NuxPayTransaction extends AbstractModel implements NuxPayTransactionInterface {

    protected function _construct() {
        $this->_init('Nuxpay\Merchant\Model\ResourceModel\NuxPayTransaction');
    }

    public function getId() {
	return $this->getData(self::ID);
    }

    public function setId($id) {
	return $this->setData(self::ID, $id);
    }

    public function getIdOrder() {
	return $this->getData(self::ID_ORDER);
    }

    public function setIdOrder($id_order) {
	return $this->setData(self::ID_ORDER, $id_order);
    }

    public function getTransactionToken() {
	return $this->getData(self::TRANSACTION_TOKEN);
    }

    public function setTransactionToken($transaction_token) {
        return $this->setData(self::TRANSACTION_TOKEN, $transaction_token);
    }

    public function getTransactionReferenceId() {
	return $this->getData(self::TRANSACTION_REFERENCE_ID);
    }

    public function setTransactionReferenceId($transaction_reference_id) {
        return $this->setData(self::TRANSACTION_REFERENCE_ID, $transaction_reference_id);
    }

    public function getTransactionAddress() {
	return $this->getData(self::TRANSACTION_ADDRESS);
    }

    public function setTransactionAddress($transaction_address) {
        return $this->setData(self::TRANSACTION_ADDRESS, $transaction_address);
    }

    public function getTransactionId() {
	return $this->getData(self::TRANSACTION_ID);
    }

    public function setTransactionId($transaction_id) {
        return $this->setData(self::TRANSACTION_ID, $transaction_id);
    }

    public function getTransactionStatus() {
	return $this->getData(self::TRANSACTION_STATUS);
    }

    public function setTransactionStatus($transaction_status){
        return $this->setData(self::TRANSACTION_STATUS, $transaction_status);
    }

    public function getAmount() {
	return $this->getData(self::AMOUNT);
    }

    public function setAmount($amount) {
        return $this->setData(self::AMOUNT, $amount);
    }

    public function getAmountCurrency() {
	return $this->getData(self::AMOUNT_CURRENCY);
    }

    public function setAmountCurrency($amount_currency) {
        return $this->setData(self::AMOUNT_CURRENCY, $amount_currency);
    }

    public function getAmountReceive() {
	return $this->getData(self::AMOUNT_RECEIVE);
    }

    public function setAmountReceive($amount_receive) {
        return $this->setData(self::AMOUNT_RECEIVE, $amount_receive);
    }

    public function getAmountReceiveCurrency() {
	return $this->getData(self::AMOUNT_RECEIVE_CURRENCY);
    }

    public function setAmountReceiveCurrency($amount_receive_currency) {
        return $this->setData(self::AMOUNT_RECEIVE_CURRENCY, $amount_receive_currency);
    }

    public function getCreatedTimestamp() {
	return $this->getData(self::CREATED_TIMESTAMP);
    }

    public function setCreatedTimestamp($created_timestamp) {
        return $this->setData(self::CREATED_TIMESTAMP, $created_timestamp);
    }

    public function getUpdatedTimestamp() {
	return $this->getData(self::UPDATED_TIMESTAMP);
    }

    public function setUpdatedTimestamp($updated_timestamp) {
        return $this->setData(self::UPDATED_TIMESTAMP, $updated_timestamp);
    }


}
