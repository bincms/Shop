'use strict';

var CategoryUpdateExtensionController = BaseController.extend({

    init: function (scope, categoryService, category) {
        this.categoryService = categoryService;
        this.category = category;
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

            this.categoryService.update({id: this.category.id},
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


    getParents: function (text) {
        return this.categoryService.query({text: text}).$promise;
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.instance = 'CategoryUpdateExtensionController';
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.getParents = this.getParents.bind(this);
        this.$scope.selected = this.getDefaultSelected();
        this.$scope.categoryModel = this.category;
        this.$scope.selected.category = this.category.parent;
    },

    destroy: function () {
    }
});

CategoryUpdateExtensionController.$inject = ['$scope', '$categoryService', 'category'];

angular.module('bincms.admin.shop').controller('CategoryUpdateExtensionController', CategoryUpdateExtensionController);