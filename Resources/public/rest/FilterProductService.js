'use strict';

var FilterProductServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {
            var resourceUrl = '/api/extension/Shop/filter';
            return $resource(resourceUrl + '/:categoryId', null, {
                update: { method: 'PUT' }
            });
        }
    ]
});

angular.module('bincms.rest').provider('$filterProductService', FilterProductServiceProvider);
