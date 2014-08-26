<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\FilterBuilder\FilterType\ODM\StringFilterType;
use BinCMS\Pagination\PaginationODM;
use Extension\Shop\Document\Manufacturer;
use Extension\Shop\Form\ManufacturerForm;
use Extension\Shop\Repository\Interfaces\ManufacturerRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class ManufacturerController
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\ManufacturerRepositoryInterface
     */
    private $manufacturerRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;

    public function __construct(ManufacturerRepositoryInterface $manufacturerRepository, ConverterService $converterService,
                                Validator $validator)
    {
        $this->manufacturerRepository = $manufacturerRepository;
        $this->converterService = $converterService;
        $this->validator = $validator;
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
        $filterBuilder->add('text', 'title', new StringFilterType());
        $filterBuilder->bindRequest($request);

        $result = $this->manufacturerRepository->findAllWithFiltered($filterBuilder, $pagination);

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
        $manufacturerForm = new ManufacturerForm();
        $manufacturerForm->bindRequest($request);

        $error = $this->validator->validate($manufacturerForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $manufacturer = new Manufacturer();
        $manufacturer->setTitle($manufacturerForm->title);
        $manufacturer->setUrl($manufacturerForm->url);

        $this->manufacturerRepository->saveEntity($manufacturer);

        return $app->json(
            $this->converterService->convert($manufacturer)
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
        $manufacturerForm = new ManufacturerForm();
        $manufacturerForm->bindRequest($request);

        $error = $this->validator->validate($manufacturerForm);
        if ($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        $manufacturer = $this->manufacturerRepository->find($id);

        if(null === $manufacturer) {
            return $app->abort(404);
        }

        $manufacturer->setTitle($manufacturerForm->title);
        $manufacturer->setUrl($manufacturerForm->url);

        $this->manufacturerRepository->saveEntity($manufacturer);

        return $app->json(
            $this->converterService->convert($manufacturer)
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
        $manufacturer = $this->manufacturerRepository->find($id);

        if(null === $manufacturer) {
            return $app->abort(404);
        }

        return $app->json(
            $this->converterService->convert($manufacturer)
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
        $manufacturer = $this->manufacturerRepository->find($id);

        if(null === $manufacturer) {
            return $app->abort(404);
        }

        $this->manufacturerRepository->removeAndFlushEntity($manufacturer);

        return $app->abort(204);
    }
}