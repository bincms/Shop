'use strict';

var OrderStatusUpdateExtensionController = BaseController.extend({

    init: function (scope, orderStatusService, orderStatus) {
        this.orderStatusService = orderStatusService;
        this.orderStatus = orderStatus;
        this._super(scope);
    },

    getDefaultOrderStatusModel: function () {
        return {
            title: ''
        };
    },

    onSave: function (form, model) {
        if (form.validate()) {
            this.orderStatusService.update({id: this.orderStatus.id},
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

    resetForm: function () {
        this.$scope.orderStatusModel = this.getDefaultOrderStatusModel();
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.instance = 'OrderStatusUpdateExtensionController';
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.orderStatusModel = this.orderStatus;
    },

    destroy: function () {
    }
});

OrderStatusUpdateExtensionController.$inject = [
    '$scope', '$orderStatusService', 'orderStatus'
];

angular.module('bincms.admin.shop').controller('OrderStatusUpdateExtensionController', OrderStatusUpdateExtensionController);