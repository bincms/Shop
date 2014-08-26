'use strict';

var CategoryServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {

            var resourceUrl = '/api/extension/Shop/category';

            return $resource(resourceUrl + '/:id', null, {
                update: { method: 'PUT' },
                getChildren: {method: 'GET', isArray: true, url: resourceUrl + '/:id/children' }
            });
        }
    ]
});

angular.module('bincms.rest').provider('$categoryService', CategoryServiceProvider);
