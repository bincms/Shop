<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\FilterBuilder\FilterType\ODM\BooleanFilterType;
use BinCMS\Pagination\PaginationODM;
use Extension\Shop\Document\Delivery;
use Extension\Shop\Form\DeliveryForm;
use Extension\Shop\Repository\Interfaces\DeliveryRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class DeliveryController
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\DeliveryRepositoryInterface
     */
    private $deliveryRepository;
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;

    public function __construct(DeliveryRepositoryInterface $deliveryRepository, ConverterService $converterService,
                                Validator $validator)
    {
        $this->deliveryRepository = $deliveryRepository;
        $this->validator = $validator;
        $this->converterService = $converterService;
    }

    /**
     * @Route(pattern="/", method="GET")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function mainAction(Application $app, Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', null);

        $pagination = null;
        if(null !== $perPage) {
            $pagination = new PaginationODM($page, $perPage);
        }

        $filterBuilder = new FilterBuilder();
        $filterBuilder->add('enabled', 'enabled', new BooleanFilterType());
        $filterBuilder->bindRequest($request);

        $result = $this->deliveryRepository->findAllWithFiltered($filterBuilder, $pagination);

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
        $deliveryForm = new DeliveryForm();
        $deliveryForm->bindRequest($request);

        $error = $this->validator->validate($deliveryForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $delivery = new Delivery();
        $delivery->setTitle($deliveryForm->title);
        $delivery->setDescription($deliveryForm->description);
        $delivery->setPrice($deliveryForm->price);
        $delivery->setEnabled($deliveryForm->enabled);

        $this->deliveryRepository->saveEntity($delivery);

        return $app->json(
            $this->converterService->convert($delivery)
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
        $deliveryForm = new DeliveryForm();
        $deliveryForm->bindRequest($request);

        $error = $this->validator->validate($deliveryForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $delivery = $this->deliveryRepository->find($id);

        if(null === $delivery) {
            return $app->abort(404);
        }

        $delivery->setTitle($deliveryForm->title);
        $delivery->setDescription($deliveryForm->description);
        $delivery->setPrice($deliveryForm->price);
        $delivery->setEnabled($deliveryForm->enabled);

        $this->deliveryRepository->saveEntity($delivery);

        return $app->json(
            $this->converterService->convert($delivery)
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
        $delivery = $this->deliveryRepository->find($id);

        if(null === $delivery) {
            return $app->abort(404);
        }

        return $app->json(
            $this->converterService->convert($delivery)
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
        $delivery = $this->deliveryRepository->find($id);

        if(null === $delivery) {
            return $app->abort(404);
        }

        $this->deliveryRepository->removeAndFlushEntity($delivery);

        return $app->abort(204);
    }
}