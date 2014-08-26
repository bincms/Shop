'use strict';

var ManufacturerCreateExtensionController = BaseController.extend({

    init: function (scope, manufacturerService) {
        this.manufacturerService = manufacturerService;
        this._super(scope);
    },

    getDefaultManufacturerModel: function () {
        return {
            title: '',
            url: ''
        };
    },

    onSave: function (form, model) {

        if (form.validate()) {
            this.manufacturerService.save(
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
        this.$scope.manufacturerModel = this.getDefaultManufacturerModel();
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.manufacturerModel = this.getDefaultManufacturerModel();
    },

    destroy: function () {
    }
});

ManufacturerCreateExtensionController.$inject = [
    '$scope', '$manufacturerService'
];

angular.module('bincms.admin.shop').controller('ManufacturerCreateExtensionController', ManufacturerCreateExtensionController);