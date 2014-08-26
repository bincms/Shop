'use strict';

var DeliveryListExtensionController = BaseController.extend({

    init: function (scope, deliveryService, locationService) {
        this.deliveryService = deliveryService;
        this.locationService = locationService;
        this.searchData = this.locationService.search();

        this._super(scope);
    },

    onSelectPage: function (page) {
        this.loadPage(page);
    },

    onDeliveryRemoveSuccessCallback: function (delivery) {
        var index = this.$scope.deliveries.indexOf(delivery);
        if (index !== -1) {
            this.$scope.deliveries.splice(index, 1);
        }
    },

    loadPage: function (page) {
        var result = this.deliveryService.get({per_page: 10, page: page}, function () {
            this.$scope.deliveries = result.items;
            this.$scope.pagination = result.pagination;
            this.locationService.search('page', page);
        }.bind(this));
    },

    onDeliveryRemoveClick: function (delivery) {
        this.deliveryService.delete(
            {id: delivery.id},
            this.onDeliveryRemoveSuccessCallback.bind(this, delivery)
        );
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onSelectPage = this.onSelectPage.bind(this);
        this.$scope.onDeliveryRemoveClick = this.onDeliveryRemoveClick.bind(this);

        var currentPage = this.searchData.page || null;
        this.loadPage(currentPage);
    },

    destroy: function () {
    }
});

DeliveryListExtensionController.$inject = [
    '$scope', '$deliveryService', '$location'
];

angular.module('bincms.admin.shop').controller('DeliveryListExtensionController', DeliveryListExtensionController);