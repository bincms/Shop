'use strict';

var DeliveryCreateExtensionController = BaseController.extend({

    init: function (scope, deliveryService) {
        this.deliveryService = deliveryService;
        this._super(scope);
    },

    getDefaultDeliveryModel: function () {
        return {
            title: '',
            description: '',
            price: 0,
            enabled: true
        };
    },

    onSave: function (form, model) {

        if (form.validate()) {
            this.deliveryService.save(
                model,
                this.saveSuccessCallback.bind(this),
                this.saveErrorCallback.bind(this, form)
            );
        }
    },

    saveSuccessCallback: function () {
        this.resetForm();
    },

    saveErrorCallback: function (form, result) {
        form.setErrors(result.data.errors);
    },

    resetForm: function () {
        this.$scope.deliveryModel = this.getDefaultDeliveryModel();
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.deliveryModel = this.getDefaultDeliveryModel();
    },

    destroy: function () {
    }
});

DeliveryCreateExtensionController.$inject = [
    '$scope', '$deliveryService'
];

angular.module('bincms.admin.shop').controller('DeliveryCreateExtensionController', DeliveryCreateExtensionController);

