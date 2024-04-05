# EBoost - Product Stock Notify

## Overview

This simple module allows the store admins to view subscribers for out of stock notifications and allows  add a stock notification for products in the product list page

## Features

+ View subscribers that registered to receive out of stock notifications
+ Turn on/off the notify stock button for product in product list page
+ Turn on/off the notify stock button for product in product detail page
+ Subscriber/un-subscriber for out of stock notifications on frontend
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

Run comandline below to deploy

```bash
php bin/magento setup:static-content:deploy
```

## Usage

### View subscribers for out of stock notifications

You can go to `Eboost > Stock Notification Subscribers` to view subscribers for out of stock notifications

![Stock Alert Subscribers Menu Screenshot](docs/stock-subscribers-menu.png)
![Stock Alert Subscribers List Screenshot](docs/stock-subscribers-list.png)

### Show notify button for product list page | product detail page

- Go to `Eboost > Configuration` to setup

![Stock Alert Configuration menu Screenshot](docs/stock-setting-menu.png)

- Go to `Product Alerts` section and expand it

![Stock Alert Configuration Section Screenshot](docs/stock-setting-section.png)

- Change  `Allow Alert When Product Comes Back in Stock`'s value  to turn On/Off the notify button. 
- Change "Display notify stock button in category page"'s value to turn On/Off the notify button on the category page.
- Change "Display notify stock button in product detail page"'s value to turn On/Off the notify button on the product page.

![Stock Alert Configuration Section Screenshot](docs/stock-setting-feature.png)

- Click `Save Config`

- Go to `Cache Management -> Flush magento cache`

- Go to frontend and check

![Stock Alert FE Screenshot](docs/stock-fe-1.png)
![Stock Alert FE Screenshot](docs/stock-fe-2.png)
![Stock Alert FE Screenshot](docs/stock-fe-3.png)
![Stock Alert FE Screenshot](docs/stock-fe-4.png)
![Stock Alert FE Screenshot](docs/stock-fe-5.png)

### Custom Email templates

1. Go to Marketing -> Email templates -> Add New Template

![Stock Alert FE Screenshot](docs/stock-fe-6.png)

2. Go to Eboost > Configuration -> Catalog-> Product Alerts section

![Stock Alert FE Screenshot](docs/stock-fe-7.png)

3. Select`Stock Alert` option ->  click `Load template` button

![Stock Alert FE Screenshot](docs/stock-fe-8.png)

4. Add a Name & add code, HTMl, CSS for content ->  click `Save template` button

![Stock Alert FE Screenshot](docs/stock-fe-9.png)

5. Repeate from step 1 to step 4 if you want to create a custom template for `Price Alert`

6. Select the new template that you have just created at step2

![Stock Alert FE Screenshot](docs/stock-fe-7.png)

## Bugs/Feature Requests & Contribution

Please do open a pull request on GitHub should you want to contribute, or create an issue.

## License
[BSD-4-Clause](http://directory.fsf.org/wiki/License:BSD_4Clause) - Do as you wish 👍