'use strict';

var ManufacturerListExtensionController = BaseController.extend({

    init: function (scope, manufacturerService, locationService) {
        this.manufacturerService = manufacturerService;
        this.locationService = locationService;
        this.searchData = this.locationService.search();
        this._super(scope);
    },

    onSelectPage: function (current) {
        this.loadPage(current);
    },

    onLoadPageClick: function (page) {
        this.loadPage(page);
    },

    onManufacturerRemoveSuccessCallback: function (manufacturer) {
        var index = this.$scope.manufactures.indexOf(manufacturer);
        if (index !== -1) {
            this.$scope.manufactures.splice(index, 1);
        }
    },

    loadPage: function (page) {

        this.$scope.currentPage = page;

        var result = this.manufacturerService.get({page: page, per_page: 15}, function () {
            this.$scope.manufactures = result.items;
            this.$scope.pagination = result.pagination;
            this.locationService.search('page', page);
        }.bind(this));
    },

    onManufacturerRemoveClick: function (manufacturer) {
        this.manufacturerService.delete(
            {id: manufacturer.id},
            this.onManufacturerRemoveSuccessCallback.bind(this, manufacturer)
        );
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.onLoadPageClick = this.onLoadPageClick.bind(this);
        this.$scope.onManufacturerRemoveClick = this.onManufacturerRemoveClick.bind(this);
        this.$scope.onSelectPage = this.onSelectPage.bind(this);

        var currentPage = this.searchData.page || null;

        this.loadPage(currentPage);
    },

    destroy: function () {
    }
});

ManufacturerListExtensionController.$inject = [
    '$scope', '$manufacturerService', '$location'
];

angular.module('bincms.admin.shop').controller('ManufacturerListExtensionController', ManufacturerListExtensionController);