'use strict';

var OrderUpdateExtensionController = BaseController.extend({

    init: function (scope, orderService, order) {
        this.orderService = orderService;
        this.order = order;
        this._super(scope);
    },

    onSave: function (form, model) {
        if (form.validate()) {
            this.orderService.save(
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
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.order = this.order;
    },

    destroy: function () {
    }
});

OrderUpdateExtensionController.$inject = [
    '$scope', '$orderService', 'order'
];

angular.module('bincms.admin.shop').controller('OrderUpdateExtensionController', OrderUpdateExtensionController);