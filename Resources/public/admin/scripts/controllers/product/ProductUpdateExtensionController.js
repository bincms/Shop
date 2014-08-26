'use strict';

var ProductUpdateExtensionController = BaseController.extend({

    init: function (scope, productService, product) {
        this.productService = productService;
        this.product = product;
        this._super(scope);
    },

    onSave: function (form, model) {
        if (form.validate()) {

            if (this.$scope.selected.category !== null && angular.isDefined(this.$scope.selected.category)) {
                model.categoryId = this.$scope.selected.category.id;
            }

            this.productService.update({id: this.product.id},
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
        this.$scope.productModel = this.product;
        this.$scope.selected = {
            category: this.product.category
        };
    },

    destroy: function () {
    }
});

ProductUpdateExtensionController.$inject = [
    '$scope', '$productService', 'product'
];

angular.module('bincms.admin.shop').controller('ProductUpdateExtensionController', ProductUpdateExtensionController);