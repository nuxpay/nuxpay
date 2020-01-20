<?php
/**
 * Nuxpay Callback controller
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Controller\Payment;

use Nuxpay\Merchant\Model\Payment as NuxpayPayment;
use Nuxpay\Merchant\Block\NuxPay;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Nuxpay\Merchant\Model\NuxPayTransaction;
use Nuxpay\Merchant\Model\ResourceModel\NuxPayTransaction\Collection;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Data\Form\FormKey;

class WalletTypes extends Action
{
    protected $nuxPay;
    protected $resultPageFactory;
    protected $scopeConfig;

    protected $request;
    protected $formKey;

    public function __construct(
        Context $context,
        NuxPay $nuxPay,
        PageFactory $resultPageFactory,
        ScopeConfigInterface $scopeConfig,
        Http $request,
        FormKey $formKey
    ) {
    
        parent::__construct($context);
        $this->nuxPay = $nuxPay;
        $this->resultPageFactory = $resultPageFactory;
        $this->scopeConfig = $scopeConfig;
        $this->request = $request;
        $this->formKey = $formKey;
        $this->request->setParam('form_key', $this->formKey->getFormKey());
    }

    /**
     * When order payment has timed out, this page is called, update payment status to On Hold
     *
     * @return void
     */
    public function execute()
    {
        // $testing = "testing";
        $api_key = $this->getRequest()->getParam('api_key');
        $business_id = $this->getRequest()->getParam('business_id');


        $params = [
            'business_id' => $business_id,
            'api_key' => $api_key
        ];
        $result = $this->nuxPay->getWalletTypes($params);

        $resultJson = $this->resultPageFactory->create();

        // return $resultJson->setData(['success' => $result]);
        return $resultJson;
        // return [];//array($testing);

    }
}
