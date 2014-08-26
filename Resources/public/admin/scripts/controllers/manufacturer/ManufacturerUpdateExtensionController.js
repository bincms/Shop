'use strict';

var ManufacturerUpdateExtensionController = BaseController.extend({

    init: function (scope, manufacturerService, manufacturer) {
        this.manufacturerService = manufacturerService;
        this.manufacturer = manufacturer;
        this._super(scope);
    },

    getDefaultManufacturerModel: function () {
        return {
            title: '',
            parent: null
        };
    },

    onSave: function (form, model) {
        if (form.validate()) {
            this.manufacturerService.update({id: this.manufacturer.id},
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
        this.$scope.manufacturerModel = this.manufacturer;
    },

    destroy: function () {
    }
});

ManufacturerUpdateExtensionController.$inject = [
    '$scope', '$manufacturerService', '$manufacturer'
];

angular.module('bincms.admin.shop').controller('ManufacturerUpdateExtensionController', ManufacturerUpdateExtensionController);