'use strict';

var OrderListExtensionController = BaseController.extend({

    init: function (scope, orderService, locationService) {
        this.orderService = orderService;
        this.locationService = locationService;
        this.searchData = this.locationService.search();
        this._super(scope);
    },

    onSelectPage: function (page) {
        this.loadPage(page);
    },

    onOrderRemoveSuccessCallback: function (order, result) {
        if (result.success) {
            var index = this.$scope.orders.indexOf(order);
            if (index !== -1) {
                this.$scope.orders.splice(index, 1);
            }
        }
    },

    loadPage: function (page) {
        var result = this.orderService.get({page: page}, function () {
            this.$scope.orders = result.items;
            this.$scope.pagination = result.pagination;
            this.locationService.search('page', page);
        }.bind(this));
    },

    onOrderRemoveClick: function (order) {
        this.orderService.delete(
            {id: order.id},
            this.onOrderRemoveSuccessCallback.bind(this, order)
        );
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.orders = [];
        this.$scope.onSelectPage = this.onSelectPage.bind(this);
        this.$scope.onOrderRemoveClick = this.onOrderRemoveClick.bind(this);

        var currentPage = this.searchData.page || null;

        this.loadPage(currentPage);
    },

    destroy: function () {
    }
});

OrderListExtensionController.$inject = [
    '$scope', '$orderService', '$location'
];

angular.module('bincms.admin.shop').controller('OrderListExtensionController', OrderListExtensionController);
