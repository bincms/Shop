<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\Pagination\PaginationODM;
use Extension\Shop\Document\OrderStatus;
use Extension\Shop\Form\OrderStatusForm;
use Extension\Shop\Repository\Interfaces\OrderStatusRepositoryInterface;
use Extension\Shop\Repository\OrderStatusRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class OrderStatusController
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\OrderStatusRepositoryInterface
     */
    private $orderStatusRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;

    public function __construct(OrderStatusRepositoryInterface $orderStatusRepository, ConverterService $converterService,
                                Validator $validator)
    {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->converterService = $converterService;
        $this->validator = $validator;
    }


    /**
     * @Route(pattern="")
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

        $result = $this->orderStatusRepository->findAllWithFiltered(null, $pagination);

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
        $orderStatusForm = new OrderStatusForm();
        $orderStatusForm->bindRequest($request);

        $error = $this->validator->validate($orderStatusForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $orderStatus = new OrderStatus();
        $orderStatus->setTitle($orderStatusForm->title);

        $this->orderStatusRepository->saveEntity($orderStatus);

        return $app->json(
            $this->converterService->convert($orderStatus)
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
        $orderStatusForm = new OrderStatusForm();
        $orderStatusForm->bindRequest($request);

        $error = $this->validator->validate($orderStatusForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $orderStatus = $this->orderStatusRepository->find($id);

        if(null === $orderStatus) {
            return $app->abort(404);
        }

        $orderStatus->setTitle($orderStatusForm->title);

        $this->orderStatusRepository->saveEntity($orderStatus);

        return $app->json(
            $this->converterService->convert($orderStatus)
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
        $orderStatus = $this->orderStatusRepository->find($id);
        return $app->json(
            $this->converterService->convert($orderStatus)
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
        $orderStatus = $this->orderStatusRepository->find($id);

        if(null === $orderStatus) {
            return $app->abort(404);
        }

        $this->orderStatusRepository->removeAndFlushEntity($orderStatus);

        return $app->abort(204);
    }
}