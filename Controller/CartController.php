<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\Pagination\PaginationODM;
use Extension\Shop\Document\Cart;
use Extension\Shop\Document\CartItem;
use Extension\Shop\Form\CartForm;
use Extension\Shop\Form\CartItemForm;
use Extension\Shop\Form\OrderStatusForm;
use Extension\Shop\Repository\Interfaces\CartRepositoryInterface;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;
use Extension\Shop\Repository\OrderStatusRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class CartController
{
    /**
     * @var \Extension\Shop\Repository\Interfaces\CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    public function __construct(CartRepositoryInterface $cartRepository, ProductRepositoryInterface $productRepository,
                                Session $session, ConverterService $converterService, Validator $validator)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->converterService = $converterService;
        $this->validator = $validator;
        $this->session = $session;
    }

    /**
     * @Route(pattern="/", method="POST")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Application $app, Request $request)
    {
        $cartForm = new CartForm();
        $cartForm->bindRequest($request);

        $errors = $this->validator->validate($cartForm);
        if (sizeof($errors) > 0) {
            return $app->json($this->converterService->convert($errors), 400);
        }

        $cart = new Cart();

        $this->cartRepository->saveEntity($cart);

        return $app->json(
            $this->converterService->convert($cart)
        );
    }

    /**
     * @Route(pattern="/item", method="POST")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addItemAction(Application $app, Request $request)
    {
        $cartItemForm = new CartItemForm();
        $cartItemForm->bindRequest($request);

        $errors = $this->validator->validate($cartItemForm);
        if (sizeof($errors) > 0) {
            return $app->json($this->converterService->convert($errors), 400);
        }

        $cart = $this->getCart($app->user());

        /** @var \Extension\Shop\Document\Product $product */
        $product = $this->productRepository->find($cartItemForm->productId);
        if (null === $product) {
            return $app->abort(500);
        }

        foreach ($cart->getItems() as $cartItem) {
            if ($cartItem->getProduct()->getId() === $cartItemForm->productId) {
                $cartItem->setQuantity($cartItemForm->quantity);

                $this->cartRepository->saveEntity($cart);

                return $app->json(
                    $this->converterService->convert($cart)
                );
            }
        }

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity($cartItemForm->quantity);
        $cart->addItem($cartItem);

        $this->cartRepository->saveEntity($cart);

        return $app->json(
            $this->converterService->convert($cart)
        );

    }

    /**
     * @Route(pattern="/item/{productId}", method="PUT")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateItemAction(Application $app, Request $request, $productId)
    {
        $cartItemForm = new CartItemForm();
        $cartItemForm->bindRequest($request);

        $errors = $this->validator->validate($cartItemForm);
        if (sizeof($errors) > 0) {
            return $app->json($this->converterService->convert($errors), 400);
        }

        $cart = $this->getCart($app->user());

        foreach ($cart->getItems() as $cartItem) {
            if ($cartItem->getProduct()->getId() === $productId) {
                $cartItem->setQuantity($cartItemForm->quantity);
                break;
            }
        }

        $this->cartRepository->saveEntity($cart);

        return $app->json(
            $this->converterService->convert($cart)
        );
    }

    /**
     * @Route(pattern="/item/{productId}", method="DELETE")
     * @param Application $app
     * @param Request $request
     * @param $productId
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeItemAction(Application $app, Request $request, $productId)
    {
        $cart = $this->getCart($app->user());

        $items = $cart->getItems();

        foreach ($items as $index => $item) {
            if ($item->getProduct()->getId() == $productId) {
                unset($items[$index]);
            }
        }

        $cart->setItems($items);

        $this->cartRepository->saveEntity($cart);

        return $app->json(
            $this->converterService->convert($cart)
        );
    }

    /**
     * @Route(pattern="/", method="GET")
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(Application $app)
    {
        $cart = $this->getCart($app->user());

        return $app->json(
            $this->converterService->convert($cart)
        );
    }

    /**
     * @Route(pattern="/", method="DELETE")
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function clearAction(Application $app)
    {
        $cart = $this->getCart($app->user());

        $cart->setItems([]);

        $this->cartRepository->saveEntity($cart);

        return $app->json(
            $this->converterService->convert($cart)
        );
    }

    private function getCart($user)
    {
        $cartId = isset($_COOKIE['cartId']) ? $_COOKIE['cartId'] : null;
        $value = $user === null ? $cartId : $user;

        $cart = $this->cartRepository->findByUserOrId($value);

        if(null === $cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $this->cartRepository->saveEntity($cart);

            if(null === $user) {
                setcookie('cartId', $cart->getId(), time()+60*60*24*360);
            }
        }


        if(null !== $user) {
            $anonymousCart = $this->cartRepository->find($cartId);
            if(null !== $anonymousCart) {
                $anonymousItems = $anonymousCart->getItems()->toArray();
                if(!empty($anonymousItems)) {
                    $items = $cart->getItems()->toArray();
                    $diffItems = array_udiff($anonymousItems, $items, function($a, $b) {
                        if($a->getProduct()->getId() === $b->getProduct()->getId()) {
                            return 0;
                        }
                        return 1;
                    });
                    $cart->setItems(array_merge($items, $diffItems));
                    $anonymousCart->setItems([]);

                    $this->cartRepository->saveEntity($cart);
                    $this->cartRepository->saveEntity($anonymousCart);
                }
            }
        }

        return $cart;
    }

}