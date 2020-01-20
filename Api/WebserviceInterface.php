<?php
/**
* Copyright © Magento, Inc. All rights reserved.
* See COPYING.txt for license details.
*/
namespace Nuxpay\Merchant\Api;
/**
* @api
*/
interface WebserviceInterface
{
  /**
   * Set default shipping address
   *
   * @return boolean|array
   */
  public function nuxpayCallback();

}
