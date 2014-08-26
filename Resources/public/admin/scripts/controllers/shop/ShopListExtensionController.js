'use strict';

var ShopListExtensionController = BaseController.extend({

    init: function (scope, shopService, locationService) {
        this.shopService = shopService;
        this.locationService = locationService;
        this.dataSearch = this.locationService.search();
        this._super(scope);
    },

    onSelectPage: function (page) {
        this.loadPage(page);
    },

    removeSuccessCallback: function (shop) {
        var index = this.$scope.shops.indexOf(shop);
        if (index !== -1) {
            this.$scope.shops.splice(index, 1);
        }
    },

    loadPage: function (page) {
        var result = this.shopService.get({page: page, per_page: 15}, function () {
            this.$scope.shops = result.items;
            this.$scope.pagination = result.pagination;
            this.locationService.search('page', page);
        }.bind(this));
    },

    onRemoveClick: function (shop) {
        this.shopService.delete(
            {id: shop.id},
            this.removeSuccessCallback.bind(this, shop)
        );
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onRemoveClick = this.onRemoveClick.bind(this);
        this.$scope.onSelectPage = this.onSelectPage.bind(this);

        var currentPage = this.dataSearch.page || 1;
        this.loadPage(currentPage);
    },

    destroy: function () {
    }
});

ShopListExtensionController.$inject = [
    '$scope', '$shopService', '$location'
];

angular.module('bincms.admin.shop').controller('ShopListExtensionController', ShopListExtensionController);
