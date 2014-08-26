<?php

namespace Extension\Shop\Controller;

use BinCMS\Annotations\Route;
use BinCMS\Converter\ConverterService;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;
use NilPortugues\Sphinx\SphinxClient;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class SearchController
{
    /**
     * @var \NilPortugues\Sphinx\SphinxClient
     */
    private $sphinxClient;
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;

    public function __construct(SphinxClient $sphinxClient, ProductRepositoryInterface $productRepository,
                                ConverterService $converterService)
    {
        $this->sphinxClient = $sphinxClient;
        $this->productRepository = $productRepository;
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
        $text = $request->get('text');
        $result = $this->sphinxClient->query($text);

        if(false === $result || !isset($result['matches'])) {
            return $app->json();
        }

        $result = array_map(function($data) {
            return $data['attrs']['_id'];
        }, $result['matches']);

        $result = $this->productRepository->loadInIds($result);

        return $app->json(
            $this->converterService->convert($result)
        );
    }

}