<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\FilterBuilder\FilterType\ODM\StringFilterType;
use Extension\Shop\Document\Category;
use Extension\Shop\Form\CategoryForm;
use Extension\Shop\Repository\Interfaces\CategoryRepositoryInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;
use BinCMS\Annotations\Auth;

class CategoryController
{
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

    public function __construct(CategoryRepositoryInterface $categoryRepository, ConverterService $converterService,
                                Validator $validator)
    {
        $this->categoryRepository = $categoryRepository;
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
        $direct = filter_var($request->get('direct', true), \FILTER_VALIDATE_BOOLEAN);

        $filterBuilder = new FilterBuilder();
        $filterBuilder->add('text', 'title', new StringFilterType());
        $filterBuilder->bindRequest($request);

        $result = $this->categoryRepository->findAllWithFilter(null, $filterBuilder, null, $direct);

        if (null === $result) {
            return $app->abort(404);
        }

        return $app->json($this->converterService->convert($result));
    }

    /**
     * @Route(pattern="/{id}/children", method="GET")
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function childrenAction(Application $app, Request $request, $id)
    {
        $category = $this->categoryRepository->find($id);

        if (null === $category) {
            return $app->abort(404);
        }

        $filterBuilder = new FilterBuilder();
        $filterBuilder->bindRequest($request);

        $children = $this->categoryRepository->findAllWithFilter($category, $filterBuilder);

        return $app->json(
            $this->converterService->convert($children, true)
        );
    }

    /**
     * @Route(pattern="/{id}", method="GET")
     * @param Application $app
     * @param $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(Application $app, Request $request, $id)
    {
        $category = $this->categoryRepository->find($id);

        if(null === $category) {
            return $app->abort(404);
        }

        return $app->json(
            $this->converterService->convert($category)
        );
    }


    /**
     * @Route(pattern="/", method="POST", secure="ROLE_ADMIN")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Application $app, Request $request)
    {
        $categoryForm = new CategoryForm();
        $categoryForm->bindRequest($request);

        $errors = $this->validator->validate($categoryForm);

        if (sizeof($errors) > 0) {
            return $app->json($this->converterService->convert($errors), 400);
        }

        $parent = $this->categoryRepository->find($categoryForm->parent);
        $category = $this->categoryRepository->create($categoryForm->title, $parent);

        return $app->json(
            $this->converterService->convert($category)
        );
    }

    /**
     * @Route(pattern="/{id}", method="PUT", secure="ROLE_ADMIN")
     * @param Application $app
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateCategoryAction(Application $app, Request $request, $id)
    {
        $categoryForm = new CategoryForm();
        $categoryForm->bindRequest($request);

        $errors = $this->validator->validate($categoryForm);

        if (sizeof($errors) > 0) {
            return $app->json($this->converterService->convert($errors), 400);
        }

        $category = $this->categoryRepository->find($id);
        $category = $this->categoryRepository->update($category, [
            'title' => $categoryForm->title
        ]);

        if (!empty($categoryForm->parent)) {
            if (null !== ($parent = $this->categoryRepository->find($categoryForm->parent))) {
                $this->categoryRepository->move($category, $parent);
            }
        }

        return $app->json(
            $this->converterService->convert($category)
        );
    }

    /**
     * @Route(pattern="/{id}", method="DELETE", secure="ROLE_ADMIN")
     * @param Application $app
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function categoryRemoveAction(Application $app, $id)
    {
        $category = $this->categoryRepository->find($id);

        $this->categoryRepository->remove($category);

        return $app->json([
            'success' => true
        ]);
    }
} 