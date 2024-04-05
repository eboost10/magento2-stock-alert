define(['jquery',
    'underscore',
    'mage/storage',
    'Magento_Ui/js/modal/alert',
    'Magento_Customer/js/customer-data',
    'uiRegistry',
    'mage/translate'
], function ($, _, storage, alert, customerData, registry, $t) {
    var defaultOptions = {
        'itemSelectorPatent': '#product-item-info_{{product_id}} .product-item-inner',
        'itemContainerSelector': '.stock.unavailable',
        productIds: [],
        'encodedUrl': "",
        'addStockUrl': "",
        'removeStockUrl': ""
    };

    var notifyStockPrototype = {
        loginPopup: false,
        init: function () {
            var notifyProductIds = this.options.productIds;
            var container = this.options.container;
            var itemSelectorPatent = this.options.itemSelectorPatent;
            var itemContainerSelector = this.options.itemContainerSelector;
            notifyProductIds.forEach(function (productId) {
                let itemSelector = itemSelectorPatent.replace('{{product_id}}', productId);
                let itemCon = $(itemSelector, container);
                if (!itemCon.is(".mail-box-con")) {
                    itemCon.append('<div class="mail-box-con" style="position: relative;" data-product_id="' + productId + '">' + '</div>');
                }
            });
            container.on("click.addAlertStock", ".mail-box-con .mail-box", this.addAlertStockEvent.bind(this));
            container.on("click.removeAlertStock", ".mail-box-con .mail-box-success", this.removeAlertStockEvent.bind(this));

            registry.get('authenticationPopup', function (popup) {
                this.loginPopup = popup;
            }.bind(this));
            this.updateSubscriber();
        },
        addAlertStock: function (productId, mailBoxCon) {
            var self = this;
            var url = this.options.addStockUrl;
            this.loading(mailBoxCon);
            return $.get(url, {'product_id': productId, 'uenc': this.options.encodedUrl})
                .done(function (response) {
                    if (response.errors) {
                        if (response.require_login) {
                            self.loginPopup.openAlertStockLoginPopup(function () {
                                self.loginPopup.closeAlertStockLoginPopup();
                                self.addAlertStock(productId, mailBoxCon);
                            });
                        } else {
                            alert({
                                content: response.error_messages.join("\n")
                            });
                        }
                    } else {
                        self._updateStockStatus(productId, true);
                        customerData.invalidate(['messages', 'stock-alert']);
                        customerData.reload(['messages', 'stock-alert'], true);
                    }
                }).fail(function () {
                }).always(function () {
                    self.removeLoading(mailBoxCon);
                });
        },
        removeAlertStock: function (productId, mailBoxCon) {
            var self = this;
            var url = this.options.removeStockUrl;
            this.loading(mailBoxCon);
            return $.post(url, {
                product: productId
            }).done(function (response) {
                if (response.errors) {
                    if (response.require_login) {
                        self.loginPopup.openAlertStockLoginPopup(function () {
                            self.loginPopup.closeAlertStockLoginPopup();
                            self.removeAlertStock(productId, mailBoxCon);
                        });
                    } else {
                        alert({
                            content: response.error_messages.join("\n")
                        });
                    }
                } else {
                    self._updateStockStatus(productId, false);
                }
            }).fail(function () {
            }).always(function () {
                self.removeLoading(mailBoxCon);
            });
        },
        updateSubscriber: function () {
            var stockData = customerData.get('stock-alert');
            stockData.subscribe(this._updateSubscriber.bind(this));
            this._updateSubscriber(stockData());
        },
        _updateSubscriber: function (stockData) {
            var self = this;
            var productIds = (!_.isEmpty(stockData.product_ids)) ? stockData.product_ids : [];

            this.options.productIds.forEach(function (productId) {
                if (productIds.indexOf(productId) > -1) {
                    self._updateStockStatus(productId, true);
                } else {
                    self._updateStockStatus(productId, false);
                }
            });
        },
        _updateStockStatus: function (productId, isNotified) {
            var container = this.options.container,
                itemSelectorPatent = this.options.itemSelectorPatent;
            let itemSelector = itemSelectorPatent.replace('{{product_id}}', productId);
            let itemCon = $(itemSelector, container).find(".mail-box-con");

            if (isNotified) {
                if (!itemCon.is(".mail-box-success")) {
                    let item = $('<div class="mail-box-success">' +
                        '    <button style="width: 100%;" class="action tocart primary" title="'+ $t('Do not notify me when this product is in stock') +'">' + $t('Un-Notify stock') + '</button>' +
                        '</div>');
                    itemCon.html(item);
                }
            } else {
                if (!itemCon.is(".mail-box")) {
                    let item = $('<div class="mail-box">' +
                        '    <button style="width: 100%;"  class="action tocart primary" title="'+ $t('Notify me when this product is in stock') +'">' + $t('Notify stock') + '</button>' +
                        '</div>');
                    itemCon.html(item);
                }
            }
        },
        loading: function (mailBoxCon) {
            mailBoxCon.addClass('_block-content-loading');
            mailBoxCon.append('<div data-role="loader" class="loading-mask" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%,-50%);width: 100%;height: 100%;margin: 0;">\n' +
                '<div class="loader"><img src="' + this.options.loadingImage + '" style="width: 30px; height: 30px; " alt="Loading..."</div>\n' +
                '</div>');
        },
        removeLoading: function (mailBoxCon) {
            mailBoxCon.find('.loading-mask').remove();
            mailBoxCon.removeClass('_block-content-loading');
        },
        addAlertStockEvent: function (event) {

            let mailBoxCon = $(event.target).closest(".mail-box-con");
            let _productId = mailBoxCon.data("product_id");
            var self = this;

            var customer = customerData.get('customer');
            if (_.isEmpty(customer())) {
                this.loginPopup.openAlertStockLoginPopup(function () {
                    self.loginPopup.closeAlertStockLoginPopup();
                    self.addAlertStock(_productId, mailBoxCon);
                });
            } else {
                self.addAlertStock(_productId, mailBoxCon);
            }
        },
        removeAlertStockEvent: function (event) {
            let mailBoxCon = $(event.target).closest(".mail-box-con");
            let _productId = mailBoxCon.data("product_id");
            var self = this;

            var customer = customerData.get('customer');
            if (_.isEmpty(customer())) {
                this.loginPopup.openAlertStockLoginPopup(function () {
                    self.loginPopup.closeAlertStockLoginPopup();
                    self.removeAlertStock(_productId, mailBoxCon);
                });
            } else {
                self.removeAlertStock(_productId, mailBoxCon);
            }
        }
    };

    var notifyStock = function (options, elem) {
        this.options = _.extend({}, defaultOptions, options);
        this.options.container = $(elem);
        this.init();
    };
    notifyStock.prototype = notifyStockPrototype;

    return function (options, elem) {
        var obj = new notifyStock(options, elem);
        return obj;
    };
});