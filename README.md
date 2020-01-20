# Nuxpay Payments #

Accept cryptocurrency payments on your Magento 2 website with Nuxpay.

## Installation ##

### From github: ###

1. Go to Magento2 root folder

2. Enter following commands to enable maintenance mode:

    ```bash
    bin/magento maintenance:enable
    ```

3. Enter following commands to install module:

    ```bash
    composer require nuxpay/module-merchant:0.0.1
    ```
   Wait while dependencies are updated.
   
4. Enter following commands to enable module:

    ```bash
    php bin/magento module:enable Nuxpay_Merchant --clear-static-content
    php bin/magento setup:upgrade
    php bin/magento setup:di:compile
    php bin/magento cache:clean
    php bin/magento cache:flush
    ```

4. Enter the following command to turn off the maintenance mode:
   ```bash
   php bin/magento maintenance:disable
   ```
    
5. Enable and configure Nuxpay in Magento Admin under Stores/Configuration/Payment Methods/nuxpay

## To Uninstall ##

1. To uninstall:

   ```bash
   bin/magento module:uninstall -r Nuxpay_Merchant
   ```

    or
    
    For modules require static view files to be cleared

   ```bash
   bin/magento module:uninstall Nuxpay_Merchant --clear-static-content
   ```

2. To remove it from all store activities:

   ```bash
   bin/magento setup:upgrade
   ```

3. To clean cache and regenerate static contents with:

   ```bash
   bin/magento cache:clean
   bin/magento setup:static-content:deploy -f
   ```

4. Enter the following command to turn off the maintenance mode:

   ```bash
   php bin/magento maintenance:disable
   ```

5. Now your Nuxpay_Merchant should be uninstalled.

