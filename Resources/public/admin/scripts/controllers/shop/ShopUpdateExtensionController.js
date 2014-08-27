'use strict';

var ShopUpdateExtensionController = BaseController.extend({

    init: function (scope, shopService, googleService, warehouses, shop, shopDaysWeek) {
        this.googleService = googleService;
        this.shopService = shopService;
        this.shop = shop;
        this.shopDaysWeek = shopDaysWeek;
        this.warehouses = warehouses;
        this._super(scope);
    },

    getDefaultShopModel: function () {
        return {
            title: ''
        };
    },

    onSave: function (form, model) {
        if (form.validate()) {
            this.shopService.update({id: this.shop.id},
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

    searchAddress: function (viewValue) {
        return this.googleService.searchAddress(viewValue);
    },

    onSelectedGoogleAddress: function (event, address) {
        var address = this.googleService.extractAddress(address);

        this.$scope.shopModel.address.country = address.country.name.long;
        this.$scope.shopModel.address.city = address.locality.name.short;
        this.$scope.shopModel.address.street = address.route ? address.route.name.short : '';
        this.$scope.shopModel.address.house = address.street_number ? address.street_number.name.short : '';

        if (address.location) {
            this.$scope.shopModel.location.lat = address.location.lat;
            this.$scope.shopModel.location.lng = address.location.lng;
        }
    },

    onAddSheduleClick: function () {

        if (null == this.$scope.shopModel.shedule) {
            this.$scope.shopModel.shedule = {
                values: []
            }
        }

        this.$scope.shopModel.shedule.values.push({});
    },

    onRemoveSheduleClick: function (index) {
        this.$scope.shopModel.shedule.values.splice(index, 1);
    },

    addWarehouse: function (warehouse) {
        if (angular.findItemWithArray(this.$scope.shopModel.warehouses, 'id', warehouse.id) === -1) {
            this.$scope.shopModel.warehouses.push(warehouse);
        }

        this.$scope.selected.warehouse = null;
    },

    removeWarehouse: function (warehouse) {
        angular.removeItemWithArray(this.$scope.shopModel.warehouses, 'id', warehouse.id);
    },

    defineListeners: function () {
        this._super();

        this.$scope.$on('$typeahead.select.googleAddress', this.onSelectedGoogleAddress.bind(this));
    },

    defineScope: function () {
        this._super();
        this.$scope.instance = 'ShopUpdateExtensionController';
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.searchAddress = this.searchAddress.bind(this);
        this.$scope.onAddSheduleClick = this.onAddSheduleClick.bind(this);
        this.$scope.onRemoveSheduleClick = this.onRemoveSheduleClick.bind(this);
        this.$scope.addWarehouse = this.addWarehouse.bind(this);
        this.$scope.removeWarehouse = this.removeWarehouse.bind(this);
        this.$scope.warehouses = this.warehouses;
        this.$scope.daysWeek = this.shopDaysWeek;
        this.$scope.selected = {
            warehouse: null
        };

        this.$scope.shopModel = this.shop;
    },

    destroy: function () {
    }
});

ShopUpdateExtensionController.$inject = [
    '$scope', '$shopService', '$googleService', 'warehouses', 'shop', 'shopDaysWeek'
];

angular.module('bincms.admin.shop').controller('ShopUpdateExtensionController', ShopUpdateExtensionController);
