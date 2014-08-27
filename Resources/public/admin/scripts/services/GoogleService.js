'use strict';

var GoogleService = Class.extend({

    init: function (httpService) {
        this.httpService = httpService;
    },

    searchAddress: function (value) {

        if (value == '') {
            return [];
        }

        var params = {address: value, sensor: false, language: 'ru'};
        return this.httpService.get('http://maps.googleapis.com/maps/api/geocode/json', {params: params})
            .then(function (response) {
                return response.data.results;
            });
    },

    extractAddress: function (address) {
        var result = [];

        result.location = address.geometry.location;

        for (var i = 0; i < address.address_components.length; i++) {
            var addressComponent = address.address_components[i];

            if (addressComponent.types.length >= 1) {
                var type = addressComponent.types[0];
                result[type] = {
                    name: {
                        long: addressComponent.long_name,
                        short: addressComponent.short_name
                    }
                };
            }
        }

        return result;
    }
});

var GoogleServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$http',
        function ($http) {
            return new GoogleService($http);
        }
    ]
});

angular.module('bincms.admin.shop').provider('$googleService', GoogleServiceProvider);



