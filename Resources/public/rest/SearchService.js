'use strict';

var SearchServiceProvider = Class.extend({
    /**
     * Initialize
     * @return {$resource}
     */
    $get: ['$resource',
        function ($resource) {
            var resourceUrl = '/api/extension/Shop/search';
            return $resource(resourceUrl);
        }
    ]
});

angular.module('bincms.rest').provider('$searchService', SearchServiceProvider);