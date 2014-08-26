'use strict';

var CategoryCreateExtensionController = BaseController.extend({

    init: function (scope, categoryService) {
        this.categoryService = categoryService;
        this._super(scope);
    },

    getDefaultCategoryModel: function () {
        return {
            title: '',
            parent: null
        };
    },

    getDefaultSelected: function () {
        return {
            category: null
        };
    },

    onSave: function (form, model) {

        if (form.validate()) {

            if (this.$scope.selected.category !== null && angular.isDefined(this.$scope.selected.category)) {
                model.parent = this.$scope.selected.category.id;
            }

            this.categoryService.save(
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

    getParents: function (text) {

        if(text == '') {
            return [];
        }

        return this.categoryService.query({text: text, text_comparison: 'endswith', direct: false}).$promise;
    },

    resetForm: function () {
        this.$scope.categoryModel = this.getDefaultCategoryModel();
        this.$scope.selected = this.getDefaultSelected();
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.getParents = this.getParents.bind(this);
        this.$scope.categoryModel = this.getDefaultCategoryModel();
        this.$scope.selected = this.getDefaultSelected();
    },

    destroy: function () {
    }
});

CategoryCreateExtensionController.$inject = ['$scope', '$categoryService'];

angular.module('bincms.admin.shop').controller('CategoryCreateExtensionController', CategoryCreateExtensionController);


