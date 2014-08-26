'use strict';

var OrderServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {
            var resourceUrl = '/api/extension/Shop/order/:id';
            return $resource(resourceUrl, null, {
                update: { method: 'PUT' },
                cancel: { method: 'POST', isArray: true, url: resourceUrl + '/cancel'}
            });
        }
    ]
});

angular.module('bincms.rest').provider('$orderService', OrderServiceProvider);