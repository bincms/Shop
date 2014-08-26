'use strict';

var CategoryListExtensionController = BaseController.extend({

    init: function (scope, categoryService, locationService) {
        this.categoryService = categoryService;
        this.locationService = locationService;
        this.searchData = this.locationService.search();
        this._super(scope);
    },

    onLoadChildClick: function (categoryId) {
        this.loadChild(categoryId);
    },

    loadChild: function (parentId) {

        if (false === parentId) {

            var categories = this.categoryService.query(function () {
                this.$scope.categories = categories;
                this.locationService.search('parentId', null);
                this.$scope.breadcrumb = [];
            }.bind(this));
            return;
        }

        var categories = this.categoryService.getChildren({id: parentId}, function () {
            if (categories.length > 0) {
                this.$scope.categories = categories;
                this.$scope.breadcrumb = categories[0].paths;
                this.locationService.search('parentId', parentId);
            }
        }.bind(this)
        );
    },

    onLoadRootChildClick: function () {
        this.loadChild(false);
    },

    onCategoryRemoveClick: function (category) {
        this.categoryService.delete(
            {id: category.id},
            this.onCategoryRemoveSuccessCallback.bind(this, category)
        );
    },

    onCategoryRemoveSuccessCallback: function (category, result) {
        if (result.success) {
            var index = this.$scope.categories.indexOf(category);
            if (index !== -1) {
                this.$scope.categories.splice(index, 1);
            }
        }
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();

        this.$scope.breadcrumb = [];
        this.$scope.onLoadChildClick = this.onLoadChildClick.bind(this);
        this.$scope.onLoadRootChildClick = this.onLoadRootChildClick.bind(this);
        this.$scope.onCategoryRemoveClick = this.onCategoryRemoveClick.bind(this);

        var parentId = this.searchData.parentId || false;

        this.loadChild(parentId);
    },

    destroy: function () {
    }
});

CategoryListExtensionController.$inject = ['$scope', '$categoryService', '$location'];

angular.module('bincms.admin.shop').controller('CategoryListExtensionController', CategoryListExtensionController);

