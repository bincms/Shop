'use strict';

var DeliveryUpdateExtensionController = BaseController.extend({

    init: function (scope, deliveryService, delivery) {
        this.deliveryService = deliveryService;
        this.delivery = delivery;
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
            this.deliveryService.update({id: this.delivery.id},
                model,
                this.saveSuccessCallback.bind(this),
                this.saveErrorCallback.bind(this, form)
            );
        }
    },

    saveSuccessCallback: function () {

    },

    saveErrorCallback: function (form, result) {
        form.setErrors(result.data.errors);
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.instance = 'DeliveryUpdateExtensionController';
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.deliveryModel = this.delivery;
    },

    destroy: function () {
    }
});

DeliveryUpdateExtensionController.$inject = [
    '$scope', '$deliveryService', 'delivery'
];

angular.module('bincms.admin.shop').controller('DeliveryUpdateExtensionController', DeliveryUpdateExtensionController);
