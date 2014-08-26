'use strict';

var CartServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {
            var resourceUrl = '/api/extension/Shop/cart';
            return $resource(resourceUrl, null, {
                clear: { method: 'DELETE', url: resourceUrl},
                addItem: { method: 'POST', url: resourceUrl + '/item'},
                removeItem: { method: 'DELETE', url: resourceUrl + '/item/:productId'},
                updateItem: { method: 'PUT', url: resourceUrl + '/item/:productId'}
            });
        }
    ]
});

angular.module('bincms.rest').provider('$cartService', CartServiceProvider);
