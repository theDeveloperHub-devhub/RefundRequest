# Overview #

Magento 2 Refund Request is the extension that allows customers to submit a refund 
request to the vendors, if there is any issue in the product. Then its upto vendor he/she 
accepts or rejects the request. Customer automatically receives the email when the
vendor change the request status.

### Features ###

* Allow customers to send the refund request in the Admin menu of Sales->Order section.
* View customer refund request in grid.
* Vendor can accept or reject the refund request.
* Send an email based on the request status.
* Vendor Customizes the popup form that will show on Account Page.

### Installation ###

1. Please run the following command
```shell
composer require thedevhub/refund-request
```

2. Update the composer if required
```shell
composer update
```

3. Enable module
```shell
php bin/magento module:enable DevHub_RefundRequest
php bin/magento setup:upgrade
php bin/magento cache:clean
php bin/magento cache:flush
```
4. If your website is running on the production mode then you need to run the following command
```shell
php bin/magento setup:static-content:deploy
php bin/magento setup:di:compile
```

