'use strict';

var ShopCreateExtensionController = BaseController.extend({

    init: function (scope, shopService, googleService, warehouses, shopDaysWeek) {
        this.shopService = shopService;
        this.googleService = googleService;
        this.warehouses = warehouses;
        this.shopDaysWeek = shopDaysWeek;
        this._super(scope);
    },

    getDefaultShopModel: function () {
        return {
            title: '',
            country: '',
            shedule: {
                values: []
            },
            city: '',
            street: '',
            house: '',
            lat: '',
            lng: '',
            warehouses: []
        };
    },

    onSave: function (form, model) {
        if (form.validate()) {
            this.shopService.save(
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

    searchAddress: function (viewValue) {
        return this.googleService.searchAddress(viewValue);
    },

    onSelectedGoogleAddress: function (event, address) {
        var address = this.googleService.extractAddress(address);
        this.$scope.shopModel.country = address.country.name.long;
        this.$scope.shopModel.city = address.locality.name.short;
        this.$scope.shopModel.street = address.route ? address.route.name.short : '';
        this.$scope.shopModel.house = address.street_number ? address.street_number.name.short : '';

        if (address.location) {
            this.$scope.shopModel.lat = address.location.lat;
            this.$scope.shopModel.lng = address.location.lng;
        }
    },

    onAddSheduleClick: function () {
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

    resetForm: function () {
        this.$scope.shopModel = this.getDefaultShopModel();
    },

    defineListeners: function () {
        this._super();

        this.$scope.$on('$typeahead.select.googleAddress', this.onSelectedGoogleAddress.bind(this));
    },

    defineScope: function () {
        this._super();
        this.$scope.onSave = this.onSave.bind(this);
        this.$scope.searchAddress = this.searchAddress.bind(this);
        this.$scope.onAddSheduleClick = this.onAddSheduleClick.bind(this);
        this.$scope.onRemoveSheduleClick = this.onRemoveSheduleClick.bind(this);
        this.$scope.addWarehouse = this.addWarehouse.bind(this);
        this.$scope.removeWarehouse = this.removeWarehouse.bind(this);
        this.$scope.warehouses = this.warehouses;
        this.$scope.selected = {
            warehouse: null
        };

        this.$scope.shopModel = this.getDefaultShopModel();
        this.$scope.daysWeek = this.shopDaysWeek;
    },

    destroy: function () {
    }
});

ShopCreateExtensionController.$inject = [
    '$scope', '$shopService', '$googleService', 'warehouses', 'shopDaysWeek'
];

angular.module('bincms.admin.shop').controller('ShopCreateExtensionController', ShopCreateExtensionController);

