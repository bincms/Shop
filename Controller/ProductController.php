<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\FilterBuilder\FilterType\ODM\BooleanFilterType;
use BinCMS\FilterBuilder\FilterType\ODM\EqualsFilterType;
use BinCMS\FilterBuilder\FilterType\ODM\InFilterType;
use BinCMS\FilterBuilder\FilterType\ODM\RangeFilterType;
use BinCMS\FilterBuilder\FilterType\ODM\StringFilterType;
use Extension\Shop\Document\Product;
use Extension\Shop\Document\ProductPrice;
use Extension\Shop\FilterBuilder\Type\ODM\ProductFilterType;
use Extension\Shop\FilterBuilder\Type\ODM\ProductInAvailableFilterType;
use Extension\Shop\Form\ProductForm;
use Extension\Shop\Repository\CategoryRepository;
use Extension\Shop\Repository\ProductRepository;
use BinCMS\Pagination\PaginationODM;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class ProductController
{
    /**
     * @var \Extension\Shop\Repository\ProductRepository
     */
    private $productRepository;

    /**
     * @var \Extension\Shop\Repository\CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;

    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                ConverterService $converterService, Validator $validator)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->converterService = $converterService;
        $this->validator = $validator;

    }

    /**
     * @Route(pattern="/", method="GET")
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function actionMain(Application $app, Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', null);

        $pagination = null;
        if(null !== $perPage) {
            $pagination = new PaginationODM($page, $perPage);
        }

        $filterBuilder = new FilterBuilder();
        $filterBuilder->add('category_id', 'category', new EqualsFilterType());
        $filterBuilder->add('sku', 'sku', new StringFilterType());
        $filterBuilder->add('leader', 'isLeader', new BooleanFilterType());
        $filterBuilder->add('ids', 'id', new InFilterType());
        $filterBuilder->add('query_filter', null, new ProductFilterType());
        $filterBuilder->add('price_range', 'price.retail', new RangeFilterType());
        $filterBuilder->add('in_available', null, new ProductInAvailableFilterType());

        $filterBuilder->bindRequest($request);

        $result = $this->productRepository->findAllWithFiltered($filterBuilder, $pagination);

        return $app->json(
            $this->converterService->convert($result)
        );
    }

    /**
     * @Route(pattern="/{id}", method="GET")
     * @param Application $app
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(Application $app, $id)
    {
        $product = $this->productRepository->find($id);

        if(null === $product) {
            $app->abort(404);
        }

        return $app->json(
            $this->converterService->convert($product, true)
        );
    }

    /**
     * @Route(pattern="/", method="POST", secure="ROLE_ADMIN,ROLE_SUPER_ADMIN")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Application $app, Request $request)
    {
        $productForm = new ProductForm();
        $productForm->bindRequest($request);

        $error = $this->validator->validate($productForm);
        if($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $price = new ProductPrice($productForm->price->retail);
        $category = $this->categoryRepository->find($productForm->categoryId);

        $product = new Product();
        $product->setSku($productForm->sku);
        $product->setTitle($productForm->title);
        $product->setPrice($price);
        $product->setDescription($productForm->description);
        $product->setAvailability($productForm->availability);
        $product->setIsLeader($productForm->isLeader);
        $product->getIsNew($productForm->isNew);

        if(null !== $category) {
            $product->setCategory($category);
        }

        $this->productRepository->saveEntity($product);

        return $app->json(
            $this->converterService->convert($category)
        );
    }

    /**
     * @Route(pattern="/{id}", method="PUT", secure="ROLE_ADMIN,ROLE_SUPER_ADMIN")
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Application $app, Request $request, $id)
    {
        $productForm = new ProductForm();
        $productForm->bindRequest($request);

        $error = $this->validator->validate($productForm);

        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        /** @var \Extension\Shop\Document\Product $product */
        $product = $this->productRepository->find($id);

        if(null === $product) {
            $app->abort(404);
        }

        $price = new ProductPrice($productForm->price->retail);
        $category = $this->categoryRepository->find($productForm->categoryId);

        $product->setSku($productForm->sku);
        $product->setTitle($productForm->title);
        $product->setPrice($price);
        $product->setDescription($productForm->description);
        $product->setAvailability($productForm->availability);
        $product->setIsLeader($productForm->isLeader);
        $product->getIsNew($productForm->isNew);

        if(null !== $category) {
            $product->setCategory($category);
        }

        $this->productRepository->saveEntity($product);

        return $app->json(
            $this->converterService->convert($product)
        );
    }

    /**
     * @Route(pattern="/{id}", method="DELETE", secure="ROLE_ADMIN,ROLE_SUPER_ADMIN")
     * @param Application $app
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Application $app, $id)
    {
        $product = $this->productRepository->find($id);

        if(null === $product) {
            $app->abort(404);
        }

        $this->productRepository->removeAndFlushEntity($product);

        return $app->abort(204);
    }
}