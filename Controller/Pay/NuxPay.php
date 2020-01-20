<?php
/**
 * Nuxpay controller
 *
 * @category    Nuxpay
 * @package     Nuxpay_Merchant
 * @author      Nuxpay
 * @copyright   Nuxpay (https://nuxpay.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Nuxpay\Merchant\Controller\Pay;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\View\Result\Page;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\Exception\LocalizedException;

class NuxPay extends Action
{

    protected $_resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct(
            $context
        );
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        return $resultPage;
    }
}
