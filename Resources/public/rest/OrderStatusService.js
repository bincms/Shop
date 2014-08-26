'use strict';

var OrderStatusServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {

            var resourceUrl = '/api/extension/Shop/order-status';

            return $resource(resourceUrl + '/:id', null, {
                update: { method: 'PUT' }
            });
        }
    ]
});

angular.module('bincms.rest').provider('$orderStatusService', OrderStatusServiceProvider);