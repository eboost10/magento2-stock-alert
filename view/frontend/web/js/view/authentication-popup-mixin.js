define(['jquery', 'EBoost_StockAlert/js/action/login','Magento_Ui/js/modal/modal'], function ($, loginAction, modal) {
    'use strict';

    var mixin = {
        _modelAlertStockLoginPopupShowing: false,
        initialize: function () {
            var self = this;

            this._super();
            loginAction.registerLoginCallback(function () {
                self.isLoading(false);
            });
        },
        setModalElement: function (element) {
            this._super();
            $(element).on('modalclosed.stock_alert', function () {
                this._modelAlertStockLoginPopupShowing = false;
            });
            this._modelAlertStockLoginPopup = $(element);
        },
        openAlertStockLoginPopup: function (callBack) {
       
            this._modelAlertStockLoginPopup.modal({type: 'popup',modalClass: 'login-popup'}).modal('openModal').trigger('contentUpdated');
            this._modelAlertStockLoginPopupShowing = callBack;
        },
        closeAlertStockLoginPopup: function () {
            this._modelAlertStockLoginPopup.modal('closeModal').trigger('contentUpdated');
        },
        login: function (formUiElement, event) {
            if (this._modelAlertStockLoginPopupShowing) {
                var loginData = {},
                    formElement = $(event.currentTarget),
                    formDataArray = formElement.serializeArray(),
                    callBack = this._modelAlertStockLoginPopupShowing;

                event.stopPropagation();
                formDataArray.forEach(function (entry) {
                    loginData[entry.name] = entry.value;
                });

                if (formElement.validation() &&
                    formElement.validation('isValid')
                ) {
                    this.isLoading(true);
                    loginAction(loginData).done(function (response) {
                        if (!response.errors) {
                            // add here
                            callBack();
                        }
                    });
                }

                return false;
            }
            return this._super();
        }
    };

    return function (target) {
        return target.extend(mixin); // new result that all other modules receive
    };
});