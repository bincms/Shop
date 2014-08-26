'use strict';

var ShopMainExtensionController = BaseController.extend({

    init: function (scope, urlService) {
        this.urlService = urlService;
        this._super(scope);
    },

    defineListeners: function () {
        this._super();
    },

    defineScope: function () {
        this._super();
        this.$scope.instance = 'ShopMainExtensionController';
        this.$scope.$content = this.urlService.view('@Shop::main');
    },

    destroy: function () {
    }
});

ShopMainExtensionController.$inject = ['$scope', '$urlService'];

angular.module('bincms.admin.shop').controller('ShopMainExtensionController', ShopMainExtensionController);
