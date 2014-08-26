'use strict';

var ProductCreateExtensionController = BaseController.extend({

    init: function (scope, productService, categoryService) {
        this.productService = productService;
        this.categoryService = categoryService;
        this._super(scope);
    },

    onSave: function (form, model) {
        if (form.validate()) {

            if (this.$scope.selected.category !== null && angular.isDefined(this.$scope.selected.category)) {
                model.categoryId = this.$scope.selected.category.id;
            }

            this.productService.save(
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

    getDefaultProductModel: function () {
        return {
            sku: '',
            title: '',
            categoryId: '',
            availability: 0,
            isLeader: false,
            isNew: false,
            price: {
                retail: 0
            }
        };
    },

    getDefaultSelected: function () {
        return {
            category: null
        };
    },

    resetForm: function () {
        this.$scope.productModel = this.getDefaultProductModel();
        this.$scope.selected = this.getDefaultSelected();
    },

    getCategories: function (text) {
        if (text != '') {
            return this.categoryService.query({text: text, text_comparison: 'endswith'}).$promise;
        }
        return [];
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.getCategories = this.getCategories.bind(this);
        this.resetForm();
    },

    destroy: function () {
    }
});

ProductCreateExtensionController.$inject = [
    '$scope', '$productService', '$categoryService'
];

angular.module('bincms.admin.shop').controller('ProductCreateExtensionController', ProductCreateExtensionController);