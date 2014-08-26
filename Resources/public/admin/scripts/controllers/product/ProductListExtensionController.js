'use strict';

var ProductListExtensionController = BaseController.extend({

    init: function (scope, productService, timeoutService, locationService, categoryId) {
        this.productService = productService;
        this.timeoutService = timeoutService;
        this.locationService = locationService;
        this.searchData = this.locationService.search();
        this.categoryId = categoryId;
        this.filterPromise = null;
        this._super(scope);
    },

    onLoadPageClick: function (page) {
        this.loadPage(page);
    },

    onProductRemoveSuccessCallback: function (product) {
        var index = this.$scope.products.indexOf(product);
        if (index !== -1) {
            this.$scope.products.splice(index, 1);
        }
    },

    onSelectPage: function (current) {
        this.loadPage(current);
    },

    loadPage: function (page, params) {

        params = angular.extend({}, params, {
            page: page,
            per_page: 15
        });

        if (null !== this.categoryId) {
            params.categoryId = this.categoryId;
        }

        var result = this.productService.get(params, function () {
            this.$scope.products = result.items;
            this.$scope.pagination = result.pagination;
            this.locationService.search('page', page);
        }.bind(this));

    },

    onProductRemoveClick: function (product) {
        this.productService.delete(
            {id: product.id},
            this.onProductRemoveSuccessCallback.bind(this, product)
        );
    },

    onFilterQueryKeyUp: function (event) {

        if (this.filterPromise != null) {
            this.timeoutService.cancel(this.filterPromise);
        }

        this.filterPromise = this.timeoutService(function () {

            var query = angular.element(event.target).val();

            if (query != '') {
                switch (this.$scope.filter.selected) {
                    case 'by_sku':
                        this.loadPage(1, {sku: query});
                        return;
                        break;
                }
            }

            this.loadPage(1);

        }.bind(this), 500);

    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();

        this.$scope.onLoadPageClick = this.onLoadPageClick.bind(this);
        this.$scope.onProductRemoveClick = this.onProductRemoveClick.bind(this);
        this.$scope.onSelectPage = this.onSelectPage.bind(this);
        this.$scope.onFilterQueryKeyUp = this.onFilterQueryKeyUp.bind(this);

        this.$scope.filter = {selected: null};
        this.$scope.filters = [
            {
                "label": "артикулу",
                "value": "by_sku"
            }
        ];

        var currentPage = this.searchData.page || null;

        this.loadPage(currentPage);
    },

    destroy: function () {
    }
});

ProductListExtensionController.$inject = ['$scope', '$productService', '$timeout', '$location', 'categoryId'];

angular.module('bincms.admin.shop').controller('ProductListExtensionController', ProductListExtensionController);