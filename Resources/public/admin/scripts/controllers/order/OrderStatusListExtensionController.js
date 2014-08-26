'use strict';

var OrderStatusListExtensionController = BaseController.extend({

    init: function (scope, orderStatusService, locationService) {
        this.orderStatusService = orderStatusService;
        this.locationService = locationService;
        this.searchData = this.locationService.search();
        this._super(scope);
    },

    onLoadPageClick: function (page) {
        this.loadPage(page);
    },

    onOrderStatusRemoveSuccessCallback: function (orderStatus) {
        var index = this.$scope.statuses.indexOf(orderStatus);
        if (index !== -1) {
            this.$scope.statuses.splice(index, 1);
        }
    },

    onSelectPage: function (current) {
        this.loadPage(current);
    },

    loadPage: function (page) {
        this.$scope.currentPage = page;
        var result = this.orderStatusService.get({page: page, per_page: 15}, function () {
            this.$scope.statuses = result.items;
            this.$scope.pagination = result.pagination;
            this.locationService.search('page', page);
        }.bind(this));
    },

    onOrderStatusRemoveClick: function (orderStatus) {
        this.orderStatusService.delete(
            {id: orderStatus.id},
            this.onOrderStatusRemoveSuccessCallback.bind(this, orderStatus)
        );
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.statuses = [];
        this.$scope.onLoadPageClick = this.onLoadPageClick.bind(this);
        this.$scope.onOrderStatusRemoveClick = this.onOrderStatusRemoveClick.bind(this);
        this.$scope.onSelectPage = this.onSelectPage.bind(this);

        var currentPage = this.searchData.page || null;

        this.loadPage(currentPage);
    },

    destroy: function () {
    }
});

OrderStatusListExtensionController.$inject = [
    '$scope', '$orderStatusService', '$location'
];

angular.module('bincms.admin.shop').controller('OrderStatusListExtensionController', OrderStatusListExtensionController);