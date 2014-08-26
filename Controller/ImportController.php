<?php

namespace Extension\Shop\Controller;

use BinCMS\DataLoader\WorkflowDataLoader;
use Extension\Shop\Action\ShopActionFactory;
use Extension\Shop\Repository\ImportProcessRepository;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use BinCMS\Annotations\Route;

class ImportController
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \Extension\Shop\Repository\ImportProcessRepository
     */
    private $importProcessRepository;
    private $importPath;
    private $appBasePath;

    public function __construct(ProductRepositoryInterface $productRepository, ImportProcessRepository $importProcessRepository,
                                $importPath, $appBasePath)
    {
        $this->productRepository = $productRepository;
        $this->importProcessRepository = $importProcessRepository;
        $this->importPath = $importPath;
        $this->appBasePath = $appBasePath;
    }

    /**
     * @Route(pattern="/", method="GET|POST")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mainAction(Application $app, Request $request)
    {
        $shopActionFactory = new ShopActionFactory(
            $this->importPath,
            $this->appBasePath,
            $this->importProcessRepository
        );

        return $shopActionFactory->make($request)->execute($request);
    }
}