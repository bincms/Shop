<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\Document\Address;
use BinCMS\Document\Location;
use BinCMS\Document\Shedule;
use BinCMS\Document\SheduleValue;
use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\Pagination\PaginationODM;
use Extension\Shop\Document\Shop;
use Extension\Shop\Form\ShopForm;
use Extension\Shop\Repository\Interfaces\ShopRepositoryInterface;
use Extension\Shop\Repository\Interfaces\WarehouseRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class ShopController
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\ShopRepositoryInterface
     */
    private $shopRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;
    /**
     * @var \Extension\Shop\Repository\Interfaces\WarehouseRepositoryInterface
     */
    private $warehouseRepository;

    public function __construct(ShopRepositoryInterface $shopRepository, WarehouseRepositoryInterface $warehouseRepository, ConverterService $converterService, Validator $validator)
    {
        $this->shopRepository = $shopRepository;
        $this->converterService = $converterService;
        $this->validator = $validator;
        $this->warehouseRepository = $warehouseRepository;
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
        $filterBuilder->bindRequest($request);

        $result = $this->shopRepository->findAllWithFiltered($filterBuilder, $pagination);

        return $app->json($this->converterService->convert($result));

    }

    /**
     * @Route(pattern="/", method="POST")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Application $app, Request $request)
    {
        $shopForm = new ShopForm();
        $shopForm->bindRequest($request);

        $error = $this->validator->validate($shopForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $shop = new Shop();
        $shopAddress = new Address();
        $shopLocation = new Location();

        $shopLocation->setLat($shopForm->lat);
        $shopLocation->setLng($shopForm->lng);

        $shopAddress->setCountry($shopForm->country);
        $shopAddress->setCity($shopForm->city);
        $shopAddress->setStreet($shopForm->street);
        $shopAddress->setHouse($shopForm->house);

        $shop->setTitle($shopForm->title);
        $shop->setAddress($shopAddress);
        $shop->setLocation($shopLocation);

        $shedule = new Shedule();
        foreach ($shopForm->shedule->values as $sheduleValue) {
            if (isset($sheduleValue['dayWeekFrom']) && isset($sheduleValue['dayWeekTo']) &&
                isset($sheduleValue['timeFrom']) && isset($sheduleValue['timeTo'])
            ) {
                $shedule->addSheduleValue(
                    new SheduleValue(
                        $sheduleValue['dayWeekFrom'],
                        $sheduleValue['dayWeekTo'],
                        new \DateTime($sheduleValue['timeFrom']),
                        new \DateTime($sheduleValue['timeTo'])
                    )
                );
            }
        }

        foreach($shopForm->warehouses as $warehouseData) {
            if(isset($warehouseData['id'])) {
                $warehouse = $this->warehouseRepository->find($warehouseData['id']);
                if(null !== $warehouse) {
                    $shop->addWarehouse($warehouse);
                }
            }
        }

        $this->shopRepository->saveEntity($shop);

        return $app->json(
            $this->converterService->convert($shop)
        );
    }

    /**
     * @Route(pattern="/{id}", method="PUT")
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Application $app, Request $request, $id)
    {
        $shopForm = new ShopForm();
        $shopForm->bindRequest($request);

        $error = $this->validator->validate($shopForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        /**
         * @var $shop \Extension\Shop\Document\Shop
         */
        $shop = $this->shopRepository->find($id);
        if (null === $shop) {
            return $app->abort(404);
        }

        if (($shopLocation = $shop->getLocation()) === null) {
            $shopLocation = new Location();
        }

        if (($shopAddress = $shop->getAddress()) === null) {
            $shopAddress = new Address();
        }

        $shopLocation->setLat($shopForm->lat);
        $shopLocation->setLng($shopForm->lng);

        $shopAddress->setCountry($shopForm->country);
        $shopAddress->setCity($shopForm->city);
        $shopAddress->setStreet($shopForm->street);
        $shopAddress->setHouse($shopForm->house);

        $shop->setTitle($shopForm->title);
        $shop->setAddress($shopAddress);
        $shop->setLocation($shopLocation);

        $shedule = new Shedule();

        foreach ($shopForm->shedule->values as $sheduleValue) {
            if (isset($sheduleValue['dayWeekFrom']) && isset($sheduleValue['dayWeekTo']) &&
                isset($sheduleValue['timeFrom']) && isset($sheduleValue['timeTo'])
            ) {
                $shedule->addSheduleValue(
                    new SheduleValue(
                        $sheduleValue['dayWeekFrom'],
                        $sheduleValue['dayWeekTo'],
                        $sheduleValue['timeFrom'],
                        $sheduleValue['timeTo']
                    )
                );
            }
        }

        $shop->setWarehouses([]);
        foreach($shopForm->warehouses as $warehouseData) {
            if(isset($warehouseData['id'])) {
                $warehouse = $this->warehouseRepository->find($warehouseData['id']);
                if(null !== $warehouse) {
                    $shop->addWarehouse($warehouse);
                }
            }
        }

        $shop->setShedule($shedule);

        $this->shopRepository->saveEntity($shop);

        return $app->json(
            $this->converterService->convert($shop)
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
        $shop = $this->shopRepository->find($id);

        if (null === $shop) {
            return $app->abort(404);
        }

        return $app->json(
            $this->converterService->convert($shop)
        );
    }

    /**
     * @Route(pattern="/{id}", method="DELETE")
     * @param Application $app
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Application $app, $id)
    {
        $shop = $this->shopRepository->find($id);

        if (null === $shop) {
            return $app->abort(404);
        }

        $this->shopRepository->removeAndFlushEntity($shop);

        return $app->json();
    }
}