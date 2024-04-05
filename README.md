# EBoost - Product Stock Notify

## Overview

This simple module allows store admins to view subscribers for out of stock notifications and allow add notify stock for product in product list page

## Features
+ Ability to view subscribers for out of stock notifications
+ Turn on/off notify stock button for product in product list page
+ Turn on/off notify stock button for product in product detail page
+ Ability to subscriber/un-subscriber for out of stock notifications on frontend
+ Support Ajax and Login popup

## Installation

Require the module

```bash
composer require eboosttech/magento2-stock-alert
```

Run setup to install module

```bash
php bin/magento setup:upgrade
```

## Usage

### View subscribers for out of stock notifications
You can go to `Eboost > Stock Notification Subscribers` to view subscribers for out of stock notifications

![Stock Alert Subscribers Menu Screenshot](docs/stock-subscribers-menu.png)
![Stock Alert Subscribers List Screenshot](docs/stock-subscribers-list.png)

### Show notify button for product list page | product detail page

Go to `Eboost > Configuration` to view setting
![Stock Alert Configuration menu Screenshot](docs/stock-setting-menu.png)

Then go to `Product Alerts` section and expand it
![Stock Alert Configuration Section Screenshot](docs/stock-setting-section.png)

Change `Allow Alert When Product Comes Back in Stock` to `Yes` if the values is `No`
Turn On/Off Notify button for product list page by changing `Display notify stock button in category page` value
Turn On/Off Notify button for product detail page by changing `Display notify stock button in product detail page` value
![Stock Alert Configuration Section Screenshot](docs/stock-setting-feature.png)

After update settings, click `Save Config` to save
Then go to cache page and flush cache
Go to frontend and check
![Stock Alert FE Screenshot](docs/stock-fe-1.png)
![Stock Alert FE Screenshot](docs/stock-fe-2.png)
![Stock Alert FE Screenshot](docs/stock-fe-3.png)
![Stock Alert FE Screenshot](docs/stock-fe-4.png)
![Stock Alert FE Screenshot](docs/stock-fe-5.png)


## Bugs/Feature Requests & Contribution

Please do open a pull request on GitHub should you want to contribute, or create an issue.

## License
[BSD-4-Clause](http://directory.fsf.org/wiki/License:BSD_4Clause) - Do as you wish 👍