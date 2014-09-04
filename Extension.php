<?php

namespace Extension\Shop;

use BinCMS\BaseExtension;
use BinCMS\DataImport\Writer\DoctrineWriter;
use Extension\Shop\Command\BuildFilterCommand;
use Extension\Shop\Command\SphinxIndexCommand;
use Extension\Shop\Converter\CartConverter;
use Extension\Shop\Converter\CartItemConverter;
use Extension\Shop\Command\ImportCatalogCommand;
use Extension\Shop\Converter\DeliveryConverter;
use Extension\Shop\Converter\FilterProductConverter;
use Extension\Shop\Converter\FilterValueConverter;
use Extension\Shop\Converter\ManufacturerConverter;
use Extension\Shop\Converter\CategoryConverter;
use Extension\Shop\Converter\OrderConverter;
use Extension\Shop\Converter\OrderProductConverter;
use Extension\Shop\Converter\OrderStatusConverter;
use Extension\Shop\Converter\ProductConverter;
use Extension\Shop\Converter\ProductPriceConverter;
use Extension\Shop\Converter\ProductPropertyConverter;
use Extension\Shop\Converter\ProductPropertyValueConverter;
use Extension\Shop\Converter\ShopConverter;
use Extension\Shop\Converter\WarehouseConverter;
use Extension\Shop\DataImport\Converter\CommerceMLCategoryConverter;
use Extension\Shop\DataImport\Converter\CommerceMLOfferConverter;
use Extension\Shop\DataImport\Converter\CommerceMLProductConverter;
use Extension\Shop\DataImport\Converter\CommerceMLPropertyConverter;
use Extension\Shop\DataImport\Converter\CommerceMLWarehouseConverter;
use Extension\Shop\Facade\ImportCategoryFacade;
use Extension\Shop\Facade\ImportOfferFacade;
use Extension\Shop\Facade\ImportProductFacade;
use Extension\Shop\Facade\ImportPropertyFacade;
use Extension\Shop\Facade\ImportWarehouseFacade;
use Extension\Shop\Sphinx\ProductReader;
use Extension\Shop\SphinxXml\SphinxLoader;
use NilPortugues\Sphinx\SphinxClient;
use Silex\Application;

class Extension extends BaseExtension
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application|\BinCMS\Application $app An Application instance
     */
    public function register(Application $app)
    {
        $importPath = $app['app.cachePath'] . '/import';

        $app
            ->registerDataRepository($this, 'Category')
            ->registerDataRepository($this, 'Product')
            ->registerDataRepository($this, 'Manufacturer')
            ->registerDataRepository($this, 'Delivery')
            ->registerDataRepository($this, 'Order')
            ->registerDataRepository($this, 'OrderStatus')
            ->registerDataRepository($this, 'ImportProcess')
            ->registerDataRepository($this, 'ProductProperty')
            ->registerDataRepository($this, 'Shop')
            ->registerDataRepository($this, 'Cart')
            ->registerDataRepository($this, 'Filter')
            ->registerDataRepository($this, 'Warehouse');

        $app['extension.shop.service.sphinx_client'] = $app->share(function () {
            $instance = new SphinxClient();

            $instance->setServer("localhost", 9312);
            $instance->setMatchMode(\SPH_MATCH_ANY);
            $instance->setMaxQueryTime(3);
            $instance->setArrayResult(true);

            return $instance;
        });

        $app['extension.shop.service.sphinx_loader'] = $app->share(function () {
            return new SphinxLoader();
        });

        $app['extension.shop.import_offer_facade'] = $app->share(function ($app) {

            return new ImportOfferFacade(
                new DoctrineWriter($app['doctrine.odm.mongodb.dm'], 'Extension\\Shop\\Document\\Product'),
                [
                    new CommerceMLOfferConverter(
                        $app['extension.shop.repository.warehouse']
                    )
                ]
            );
        });

        $app['extension.shop.import_warehouse_facade'] = $app->share(function ($app) {

            return new ImportWarehouseFacade(
                new DoctrineWriter($app['doctrine.odm.mongodb.dm'], 'Extension\\Shop\\Document\\Warehouse'),
                [
                    new CommerceMLWarehouseConverter()
                ]
            );

        });

        $app['extension.shop.import_product_facade'] = $app->share(function ($app) use ($importPath) {
            return new ImportProductFacade(
                new DoctrineWriter($app['doctrine.odm.mongodb.dm'], 'Extension\\Shop\\Document\\Product'),
                [
                    new CommerceMLProductConverter(
                        $app['extension.shop.repository.category'],
                        $app['extension.shop.repository.product_property'],
                        $app['bincms.services.file_uploader_factory'],
                        $importPath
                    )
                ]
            );
        });

        $app['extension.shop.import_category_facade'] = $app->share(function ($app) {
            return new ImportCategoryFacade(
                new DoctrineWriter($app['doctrine.odm.mongodb.dm'], 'Extension\\Shop\\Document\\Category'),
                [
                    new CommerceMLCategoryConverter()
                ],
                [
                ]
            );
        });


        $app['extension.shop.import_property_facade'] = $app->share(function ($app) {
            return new ImportPropertyFacade(
                new DoctrineWriter($app['doctrine.odm.mongodb.dm'], 'Extension\\Shop\\Document\\ProductProperty'),
                [
                    new CommerceMLPropertyConverter()
                ]
            );
        });

        $app
            ->registerExtensionController($this, 'Controller\\CategoryController', 'category', function ($app) {
                return [
                    $app['extension.shop.repository.category'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\ProductController', 'product', function ($app) {
                return [
                    $app['extension.shop.repository.product'],
                    $app['extension.shop.repository.category'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\ManufacturerController', 'manufacturer', function ($app) {
                return [
                    $app['extension.shop.repository.manufacturer'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\DeliveryController', 'delivery', function ($app) {
                return [
                    $app['extension.shop.repository.delivery'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\OrderController', 'order', function ($app) {
                return [
                    $app['extension.shop.repository.order'],
                    $app['extension.shop.repository.shop'],
                    $app['extension.shop.repository.product'],
                    $app['extension.shop.repository.delivery'],
                    $app['extension.shop.repository.cart'],
                    $app['bincms.repository.counter'],
                    $app['service.converter'],
                    $app['validator'],
                    $app['bincms.service.mailer'],
                    $app['bincms.service.message_builder'],
                    $app['dispatcher'],
                    $this->config['reserveDays']
                ];
            })
            ->registerExtensionController($this, 'Controller\\ImportController', 'import', function ($app) use ($importPath) {
                return [
                    $app['extension.shop.repository.product'],
                    $app['extension.shop.repository.import_process'],
                    $importPath,
                    $app['app.basePath']
                ];
            })
            ->registerExtensionController($this, 'Controller\\OrderStatusController', 'order-status', function ($app) {
                return [
                    $app['extension.shop.repository.order_status'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\ShopController', 'shop', function ($app) {
                return [
                    $app['extension.shop.repository.shop'],
                    $app['extension.shop.repository.warehouse'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\WarehouseController', 'warehouse', function ($app) {
                return [
                    $app['extension.shop.repository.warehouse'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\CartController', 'cart', function ($app) {
                return [
                    $app['extension.shop.repository.cart'],
                    $app['extension.shop.repository.product'],
                    $app['session'],
                    $app['service.converter'],
                    $app['validator']
                ];
            })
            ->registerExtensionController($this, 'Controller\\FilterController', 'filter', function ($app) {
                return [
                    $app['extension.shop.repository.product_property'],
                    $app['extension.shop.repository.product'],
                    $app['extension.shop.repository.filter'],
                    $app['service.converter'],
                ];
            })
            ->registerExtensionController($this, 'Controller\\SearchController', 'search', function ($app) {
                return [
                    $app['extension.shop.service.sphinx_client'],
                    $app['extension.shop.repository.product'],
                    $app['service.converter'],
                ];
            });

        $app['converter_factory']
            ->registerConverter('Extension\\Shop\\Document\\Category', new CategoryConverter())
            ->registerConverter('Extension\\Shop\\Document\\Product', new ProductConverter())
            ->registerConverter('Extension\\Shop\\Document\\ProductPrice', new ProductPriceConverter())
            ->registerConverter('Extension\\Shop\\Document\\Manufacturer', new ManufacturerConverter())
            ->registerConverter('Extension\\Shop\\Document\\Delivery', new DeliveryConverter())
            ->registerConverter('Extension\\Shop\\Document\\Order', new OrderConverter())
            ->registerConverter('Extension\\Shop\\Document\\OrderStatus', new OrderStatusConverter())
            ->registerConverter('Extension\\Shop\\Document\\ProductProperty', new ProductPropertyConverter())
            ->registerConverter('Extension\\Shop\\Document\\ProductPropertyValue', new ProductPropertyValueConverter())
            ->registerConverter('Extension\\Shop\\Document\\Cart', new CartConverter())
            ->registerConverter('Extension\\Shop\\Document\\CartItem', new CartItemConverter())
            ->registerConverter('Extension\\Shop\\Document\\Shop', new ShopConverter())
            ->registerConverter('Extension\\Shop\\Document\\OrderProduct', new OrderProductConverter())
            ->registerConverter('Extension\\Shop\\Document\\Filter', new FilterProductConverter())
            ->registerConverter('Extension\\Shop\\Document\\FilterValue', new FilterValueConverter())
            ->registerConverter('Extension\\Shop\\Document\\Warehouse', new WarehouseConverter());
    }

    public function getCommands(Application $app)
    {
        $app['extension.shop.service.sphinx_loader']->registerReader(new ProductReader($app['extension.shop.repository.product']));

        return [
            new ImportCatalogCommand(
                $app['extension.shop.repository.import_process'],
                [
                    $app['extension.shop.import_category_facade'],
                    $app['extension.shop.import_property_facade'],
                    $app['extension.shop.import_product_facade'],
                ],
                $app['extension.shop.import_offer_facade'],
                $app['extension.shop.import_warehouse_facade'],
                $app['extension.shop.repository.shop']
            ),
            new BuildFilterCommand(
                $app['extension.shop.repository.product'],
                $app['extension.shop.repository.filter']
            ),
            new SphinxIndexCommand(
                $app['extension.shop.service.sphinx_loader']
            )
        ];
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
    }
}