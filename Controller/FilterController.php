<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use Extension\Shop\Collection\FilterCollection;
use Extension\Shop\Document\Filter;
use Extension\Shop\Document\FilterValue;
use Extension\Shop\Document\ProductProperty;
use Extension\Shop\Repository\FilterRepository;
use BinCMS\Annotations\Route;
use Extension\Shop\Repository\Interfaces\ProductPropertyRepositoryInterface;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class FilterController
{
    /**
     * @var \Extension\Shop\Repository\FilterRepository
     */
    private $filterRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductPropertyRepositoryInterface
     */
    private $productPropertyRepository;

    public function __construct(ProductPropertyRepositoryInterface $productPropertyRepository, ProductRepositoryInterface $productRepository, FilterRepository $filterRepository, ConverterService $converterService)
    {
        $this->filterRepository = $filterRepository;
        $this->converterService = $converterService;
        $this->productRepository = $productRepository;
        $this->productPropertyRepository = $productPropertyRepository;
    }

    /**
     * @Route(pattern="/{categoryId}", method="GET")
     * @param Application $app
     * @param Request $request
     * @param $categoryId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mainAction(Application $app, Request $request, $categoryId)
    {
        $filters = new FilterCollection();


        $products = $this->productRepository->findAllWithCategoryId($categoryId);
        /** @var \Extension\Shop\Document\Product[] $products */
        foreach ($products as $product) {

            $valueProperties = $product->getProperties();
            if ($valueProperties->count() > 0) {

                foreach ($valueProperties as $valueProperty) {

                    $property = $valueProperty->getProperty();
                    $value = $valueProperty->getValue();
                    $title = $value;

                    $filter = $filters->getFilterByPropertyId($property->getId());

                    if (null === $filter) {

                        $filter = new Filter();
                        $filter->setCategoryId($categoryId);
                        $filter->setPropertyId($property->getId());
                        $filter->setTitle($property->getTitle());

                        $filters[] = $filter;
                    }
                    if ($property->getType() == ProductProperty::TYPE_DIRECTORY) {
                        $options = $property->getOptions();

                        if (isset($options[$value])) {
                            $title = $options[$value]->getValue();
                        }
                    }

                    $filterValue = new FilterValue($title, $value);
                    $filter->addValue($filterValue);
                }
            }
        }

        $filters = $filters->filter(function($filter) {
            return sizeof($filter->getValues()) > 0;
        });


        return $app->json(
            $this->converterService->convert($filters->getValues())
        );
    }

}