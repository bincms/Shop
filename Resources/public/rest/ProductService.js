'use strict';

var ProductServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {
            var resourceUrl = '/api/extension/Shop/product';
            return $resource(resourceUrl + '/:id', null, {
                update: { method: 'PUT' }
            });
        }
    ]
});

angular.module('bincms.rest').provider('$productService', ProductServiceProvider);