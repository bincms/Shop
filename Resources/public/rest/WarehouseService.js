'use strict';

var WarehouseServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {

            var resourceUrl = '/api/extension/Shop/warehouse';

            return $resource(resourceUrl + '/:id', null, {
                update: { method: 'PUT' },
                delete: { method: 'DELETE', isArray: true }
            });
        }
    ]
});

angular.module('bincms.rest').provider('$warehouseService', WarehouseServiceProvider);