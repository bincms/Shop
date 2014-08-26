var config = {
    title: 'Интернет магазин',
    menu: {
        items: [
            {
                title: 'Магазины',
                url: 'extension.shop.list',
                items: [
                    {title: 'Список', url: 'extension.shop.list'},
                    {title: 'Добавить', url: 'extension.shop.create'}
                ]
            },
            {
                title: 'Категории',
                url: 'extension.shop.category.list',
                items: [
                    {title: 'Список', url: 'extension.shop.category.list'},
                    {title: 'Добавить', url: 'extension.shop.category.create'}
                ]
            },
            {
                title: 'Доставка',
                url: 'extension.shop.delivery.list',
                items: [
                    {title: 'Список', url: 'extension.shop.delivery.list'},
                    {title: 'Добавить', url: 'extension.shop.delivery.create'}
                ]
            },
            {
                title: 'Производители',
                url: 'extension.shop.manufacturer.list',
                items: [
                    {title: 'Список', url: 'extension.shop.manufacturer.list'},
                    {title: 'Добавить', url: 'extension.shop.manufacturer.create'}
                ]
            },
            {
                title: 'Заказы',
                url: 'extension.shop.order.list',
                items: [
                    {title: 'Список', url: 'extension.shop.order.list'},
                    {title: 'Добавить', url: 'extension.shop.order.create'}
                ]
            },
            {
                title: 'Статусы заказа',
                url: 'extension.shop.order.status.list',
                items: [
                    {title: 'Список', url: 'extension.shop.order.status.list'},
                    {title: 'Добавить', url: 'extension.shop.order.status.create'}
                ]
            },
            {
                title: 'Товары',
                url: 'extension.shop.product.list',
                items: [
                    {title: 'Список', url: 'extension.shop.product.list'},
                    {title: 'Добавить', url: 'extension.shop.product.create'}
                ]
            }
        ]
    }
};

angular.adminModule('shop', [
        'ui.router', 'bincms.rest', 'bincms.admin.shop.templates'
    ], config)
    .constant('shopDaysWeek', [
        {value: 'Пн'},
        {value: 'Вт'},
        {value: 'Ср'},
        {value: 'Чт'},
        {value: 'Пт'},
        {value: 'Сб'},
        {value: 'Вс'}
    ])
    .config(['$stateProvider', function ($stateProvider) {

        $stateProvider
            .state('extension.shop', {
                url: '/shop'
            })
            .state('extension.shop.list', {
                url: '/list',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/list.html',
                        controller: 'ShopListExtensionController'
                    }
                }
            })
            .state('extension.shop.create', {
                url: '/create',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/create.html',
                        controller: 'ShopCreateExtensionController',
                        resolve: {
                            warehouses: ['$warehouseService', function(warehouseService) {
                                return warehouseService.query().$promise;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.update', {
                url: '/update/:id',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/update.html',
                        controller: 'ShopUpdateExtensionController',
                        resolve: {
                            shop: ['$shopService', '$stateParams', function(shopService, stateParams) {
                                return shopService.get({id: stateParams.id}).$promise;
                            }],
                            warehouses: ['$warehouseService', function(warehouseService) {
                                return warehouseService.query().$promise;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.category', {
                url: '/category'
            })
            .state('extension.shop.category.list', {
                url: '/list',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/category/list.html',
                        controller: 'CategoryListExtensionController'
                    }
                }
            })
            .state('extension.shop.category.create', {
                url: '/create',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/category/create.html',
                        controller: 'CategoryCreateExtensionController'
                    }
                }
            })
            .state('extension.shop.category.update', {
                url: '/update/:id',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/category/update.html',
                        controller: 'CategoryUpdateExtensionController',
                        resolve: {
                            category: ['$categoryService', '$stateParams', function(categoryService, stateParams) {
                                return categoryService.get({id: stateParams.id}).$promise;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.delivery', {
                url: '/delivery'
            })
            .state('extension.shop.delivery.list', {
                url: '/list',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/delivery/list.html',
                        controller: 'DeliveryListExtensionController'
                    }
                }
            })
            .state('extension.shop.delivery.create', {
                url: '/create',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/delivery/create.html',
                        controller: 'DeliveryCreateExtensionController'
                    }
                }
            })
            .state('extension.shop.delivery.update', {
                url: '/update/:id',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/delivery/update.html',
                        controller: 'DeliveryUpdateExtensionController',
                        resolve: {
                            delivery: ['$deliveryService', '$stateParams', function(deliveryService, stateParams) {
                                return deliveryService.get({id: stateParams.id}).$promise;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.manufacturer', {
                url: '/manufacturer'
            })
            .state('extension.shop.manufacturer.list', {
                url: '/list',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/manufacturer/list.html',
                        controller: 'ManufacturerListExtensionController'
                    }
                }
            })
            .state('extension.shop.manufacturer.create', {
                url: '/create',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/manufacturer/create.html',
                        controller: 'ManufacturerCreateExtensionController'
                    }
                }
            })
            .state('extension.shop.manufacturer.update', {
                url: '/update/:id',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/manufacturer/update.html',
                        controller: 'ManufacturerUpdateExtensionController',
                        resolve: {
                            manufacturer: ['$manufacturerService', '$stateParams', function(manufacturerService, stateParams) {
                                return manufacturerService.get({id: stateParams.id}).$promise;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.order', {
                url: '/order'
            })
            .state('extension.shop.order.list', {
                url: '/list',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/order/list.html',
                        controller: 'OrderListExtensionController'
                    }
                }
            })
            .state('extension.shop.order.create', {
                url: '/create',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/order/create.html',
                        controller: 'OrderCreateExtensionController'
                    }
                }
            })
            .state('extension.shop.order.update', {
                url: '/update/:id',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/order/update.html',
                        controller: 'OrderUpdateExtensionController',
                        resolve: {
                            manufacturer: ['$orderService', '$stateParams', function(orderService, stateParams) {
                                return orderService.get({id: stateParams.id}).$promise;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.order.status', {
                url: '/status'
            })
            .state('extension.shop.order.status.list', {
                url: '/list',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/order/status/list.html',
                        controller: 'OrderStatusListExtensionController'
                    }
                }
            })
            .state('extension.shop.order.status.create', {
                url: '/create',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/order/status/create.html',
                        controller: 'OrderStatusCreateExtensionController'
                    }
                }
            })
            .state('extension.shop.order.status.update', {
                url: '/update/:id',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/order/status/update.html',
                        controller: 'OrderStatusUpdateExtensionController',
                        resolve: {
                            orderStatus: ['$orderStatusService', '$stateParams', function(orderStatusService, stateParams) {
                                return orderStatusService.get({id: stateParams.id}).$promise;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.product', {
                url: '/product'
            })
            .state('extension.shop.product.list', {
                url: '/list',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/product/list.html',
                        controller: 'ProductListExtensionController',
                        resolve: {
                            categoryId: ['$location', function(locationService) {
                                return locationService.search().categoryId || null;
                            }]
                        }
                    }
                }
            })
            .state('extension.shop.product.create', {
                url: '/create',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/product/create.html',
                        controller: 'ProductCreateExtensionController'
                    }
                }
            })
            .state('extension.shop.product.update', {
                url: '/update/:id',
                views: {
                    'content@layout': {
                        templateUrl: 'admin/shop/product/update.html',
                        controller: 'ProductUpdateExtensionController',
                        resolve: {
                            product: ['$productService', '$stateParams', function(productService, stateParams) {
                                return productService.get({id: stateParams.id}).$promise;
                            }]
                        }
                    }
                }
            })
        ;
    }]);