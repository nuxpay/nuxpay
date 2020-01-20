<?php
/**
 * Nuxpay interface for transactions
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Api\Data;

interface NuxPayTransactionInterface
{
    const ID            = 'id';
    const ID_ORDER      = 'order_id';
    const TRANSACTION_TOKEN     = 'transaction_token';
    const TRANSACTION_REFERENCE_ID = 'transaction_reference_id';
    const TRANSACTION_ADDRESS = 'transaction_address';
    const TRANSACTION_ID = 'transaction_id';
    const TRANSACTION_STATUS = 'transaction_status';
    const AMOUNT = 'amount';
    const AMOUNT_CURRENCY = 'amount_currency';
    const AMOUNT_RECEIVE = 'amount_receive';
    const AMOUNT_RECEIVE_CURRENCY = 'amount_receive_currency';
    const CREATED_TIMESTAMP = 'created_timestamp';
    const UPDATED_TIMESTAMP = 'updated_timestamp';

    public function getId();
    public function setId($id);

    public function getIdOrder();
    public function setIdOrder($id_order);

    public function getTransactionToken();
    public function setTransactionToken($transaction_token);

    public function getTransactionReferenceId();
    public function setTransactionReferenceId($transaction_reference_id);

    public function getTransactionAddress();
    public function setTransactionAddress($transaction_address);

    public function getTransactionId();
    public function setTransactionId($transaction_id);

    public function getTransactionStatus();
    public function setTransactionStatus($transaction_status);

    public function getAmount();
    public function setAmount($amount);

    public function getAmountCurrency();
    public function setAmountCurrency($amount_currency);

    public function getAmountReceive();
    public function setAmountReceive($amount_receive);

    public function getAmountReceiveCurrency();
    public function setAmountReceiveCurrency($amount_receive_currency);

    public function getCreatedTimestamp();
    public function setCreatedTimestamp($created_timestamp);

    public function getUpdatedTimestamp();
    public function setUpdatedTimestamp($updated_timestamp);

}
