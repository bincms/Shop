'use strict';

var OrderStatusCreateExtensionController = BaseController.extend({

    init: function (scope, orderStatusService) {
        this.orderStatusService = orderStatusService;
        this._super(scope);
    },

    getDefaultOrderStatusModel: function () {
        return {
            title: ''
        };
    },

    onSave: function (form, model) {

        if (form.validate()) {
            this.orderStatusService.save(
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
        this.$scope.orderStatusModel = this.getDefaultOrderStatusModel();
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.orderStatusModel = this.getDefaultOrderStatusModel();
    },

    destroy: function () {
    }
});

OrderStatusCreateExtensionController.$inject = [
    '$scope', '$orderStatusService'
];

angular.module('bincms.admin.shop').controller('OrderStatusCreateExtensionController', OrderStatusCreateExtensionController);