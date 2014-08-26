'use strict';

var ManufacturerServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {

            var resourceUrl = '/api/extension/Shop/manufacturer';

            return $resource(resourceUrl + '/:id', null, {
                update: { method: 'PUT' }
            });
        }
    ]
});

angular.module('bincms.rest').provider('$manufacturerService', ManufacturerServiceProvider);