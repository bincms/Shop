<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\FilterBuilder\FilterType\ODM\StringFilterType;
use BinCMS\Pagination\PaginationODM;
use Extension\Shop\Repository\Interfaces\WarehouseRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class WarehouseController
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\WarehouseRepositoryInterface
     */
    private $warehouseRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;

    public function __construct(WarehouseRepositoryInterface $warehouseRepository, ConverterService $converterService,
                                Validator $validator)
    {
        $this->warehouseRepository = $warehouseRepository;
        $this->converterService = $converterService;
        $this->validator = $validator;
    }

    /**
     * @Route(pattern="/", method="GET")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function actionMain(Application $app, Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', null);

        $pagination = null;
        if (null !== $perPage) {
            $pagination = new PaginationODM($page, $perPage);
        }

        $filterBuilder = new FilterBuilder();
        $filterBuilder->add('text', 'title', new StringFilterType());
        $filterBuilder->bindRequest($request);

        $result = $this->warehouseRepository->findAllWithFiltered($filterBuilder, $pagination);

        return $app->json($this->converterService->convert($result));

    }
}