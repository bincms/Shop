<?php

namespace Extension\Shop\Controller;

use BinCMS\Converter\ConverterService;
use BinCMS\Document\Address;
use BinCMS\Document\User;
use BinCMS\FilterBuilder\FilterBuilder;
use BinCMS\FilterBuilder\FilterType\ODM\BooleanFilterType;
use BinCMS\FilterBuilder\FilterType\ODM\EqualsFilterType;
use BinCMS\Pagination\PaginationODM;
use BinCMS\Repository\CounterRepository;
use BinCMS\Service\MessageBuilder\MessageBuilder;
use Extension\Shop\Document\Order;
use Extension\Shop\Document\OrderProduct;
use Extension\Shop\Event\Order\OrderCreateAfterEvent;
use Extension\Shop\Event\Order\OrderCreateBeforeEvent;
use Extension\Shop\Form\OrderForm;
use Extension\Shop\Repository\Interfaces\CartRepositoryInterface;
use Extension\Shop\Repository\Interfaces\DeliveryRepositoryInterface;
use Extension\Shop\Repository\Interfaces\ProductRepositoryInterface;
use Extension\Shop\Repository\Interfaces\ShopRepositoryInterface;
use Extension\Shop\Repository\OrderRepository;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Validator\Validator;
use BinCMS\Annotations\Route;

class OrderController
{
    /**
     * @var \Extension\Shop\Repository\OrderRepository
     */
    private $orderRepository;
    /**
     * @var \BinCMS\Converter\ConverterService
     */
    private $converterService;
    /**
     * @var \Symfony\Component\Validator\Validator
     */
    private $validator;
    /**
     * @var \Extension\Shop\Repository\Interfaces\ShopRepositoryInterface
     */
    private $shopRepository;
    /**
     * @var \Extension\Shop\Repository\Interfaces\ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var \Extension\Shop\Repository\Interfaces\DeliveryRepositoryInterface
     */
    private $deliveryRepository;
    /**
     * @var \BinCMS\Repository\CounterRepository
     */
    private $counterRepository;

    /**
     * @var int
     */
    private $reserveDays;

    /**
     * @var \Nette\Mail\IMailer
     */
    private $mailer;
    /**
     * @var \BinCMS\Service\MessageBuilder\MessageBuilder
     */
    private $messageBuilder;
    /**
     * @var \Extension\Shop\Repository\Interfaces\CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $eventDispatcher;

    public function __construct(OrderRepository $orderRepository, ShopRepositoryInterface $shopRepository,
                                ProductRepositoryInterface $productRepository, DeliveryRepositoryInterface $deliveryRepository,
                                CartRepositoryInterface $cartRepository, CounterRepository $counterRepository,
                                ConverterService $converterService, Validator $validator,
                                IMailer $mailer, MessageBuilder $messageBuilder, EventDispatcher $eventDispatcher, $reserveDays)
    {
        $this->shopRepository = $shopRepository;
        $this->orderRepository = $orderRepository;
        $this->converterService = $converterService;
        $this->validator = $validator;
        $this->productRepository = $productRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->counterRepository = $counterRepository;
        $this->messageBuilder = $messageBuilder;
        $this->mailer = $mailer;
        $this->cartRepository = $cartRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->reserveDays = $reserveDays;
    }

    /**
     * @Route(pattern="/", method="GET", secure="ROLE_USER,ROLE_ADMIN,ROLE_SUPER_ADMIN")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function actionMain(Application $app, Request $request)
    {
        $customer = $app['security']->getToken()->getUser();

        if(!$app['security']->isGranted([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])) {
            $request->query->set('customer', $customer->getId());
//            $request->query->set('canceled', false);
        }

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $pagination = new PaginationODM($page, $perPage);

        $filterBuilder = new FilterBuilder();
        $filterBuilder->add('customer', 'customer', new EqualsFilterType());
        $filterBuilder->add('canceled', 'isCanceled', new BooleanFilterType());

        $filterBuilder->bindRequest($request);

        $result = $this->orderRepository->findAllWithFiltered($filterBuilder, $pagination);

        return $app->json($this->converterService->convert($result));
    }

    /**
     * @Route(pattern="/", method="POST", secure="ROLE_USER,ROLE_ADMIN,ROLE_SUPER_ADMIN")
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Application $app, Request $request)
    {
        $this->eventDispatcher->dispatch(OrderCreateBeforeEvent::NAME, new OrderCreateBeforeEvent($request));

        $currentUser = $app->user();

        if(null === $currentUser) {
            return $app->abort(401);
        }

        $orderForm = new OrderForm();
        $orderForm->bindRequest($request);

        $orderFormGroup = $orderForm->deliveryType===DeliveryRepositoryInterface::TP_PICKUP ? 'pickup' : null;

        $error = $this->validator->validate($orderForm, $orderFormGroup);
        if($error->count() > 0) {
            return $app->json($this->converterService->convert($error), 400);
        }

        if(!is_array($orderForm->items)) {
            $app->abort(500);
        }

        /** @var \Extension\Shop\Document\Shop $shop */
        $shop = null;
        $delivery = null;
        $reserve = new \DateTime();
        $reserve->add(new \DateInterval(sprintf('P%dD', $this->reserveDays)));

        $order = new Order();

        if($orderForm->deliveryType === DeliveryRepositoryInterface::TP_DELIVERY) {
            $delivery = $this->deliveryRepository->find($orderForm->deliveryId);
            if(null === $delivery) {
                $app->abort(500);
            }

            $address = new Address();
            $address->setCity($orderForm->address->city);
            $address->setPostcode($orderForm->address->postcode);
            $address->setStreet($orderForm->address->street);
            $address->setHouse($orderForm->address->house);

            $order->setAddress($address);
            $order->setDelivery($delivery);
        } else {
            $shop = $this->shopRepository->find($orderForm->shopId);
            if(null === $shop) {
                $app->abort(500);
            }
            $order->setAddress($shop->getAddress());
        }

        $orderProducts = [];
        $noProducts = [];
        foreach($orderForm->items as $item) {
            if(isset($item['id']) && isset($item['quantity'])) {
                /** @var \Extension\Shop\Document\Product $product */
                $product = $this->productRepository->find($item['id']);
                if(null !== $product) {
                    if($orderForm->deliveryType === DeliveryRepositoryInterface::TP_PICKUP) {
                        $productAvailability = 0;
                        $availability = $product->getAvailability();
                        $warehouses = $shop->getWarehouses();
                        foreach($warehouses as $warehouse) {
                            if(isset($availability[$warehouse->getId()])) {
                                $productAvailability += $availability[$warehouse->getId()];
                            }
                        }

//                        if($productAvailability < $item['quantity']) {
//                            $noProducts[] = $product;
//                        }
                    }

                    $orderProduct = new OrderProduct();
                    $orderProduct->setProductId($product->getId());
                    $orderProduct->setTitle($product->getTitle());
                    $orderProduct->setPrice($product->getPrice());
                    $orderProduct->setQuantity($item['quantity']);
                    $orderProducts[] = $orderProduct;
                }
            }
        }

//        if(sizeof($noProducts) > 0) {
//            return $app->json([
//                'noProducts' => $this->converterService->convert($noProducts)
//            ], 400);
//        }

        $orderNumber = $this->counterRepository->getCounter('order')->getValue();

        $order->setReserve($reserve);
        $order->setNumber($orderNumber);
        $order->setProducts($orderProducts);
        $order->setComment($orderForm->comment);
        $order->setCustomer($currentUser);

        $this->orderRepository->saveEntity($order);

        $this->eventDispatcher->dispatch(OrderCreateAfterEvent::NAME, new OrderCreateAfterEvent($order));

        return $app->json($this->converterService->convert($order));

//        try {
//            $message = $this->messageBuilder->build('extension.Shop.order_user_create', [
//                'order' => $order,
//                'customer' => $currentUser
//            ]);
//            $message->addTo($currentUser->getEmail());
//
//            $this->mailer->send($message);

//        $adminMessage
//            ->setFrom('order@etecom.ru')
//            ->addTo('')
//            ->setSubject('Новый заказ в Etecom')
//            ->setBody(strtr('Создан новый заказ № {orderNo}.', [
//                '{orderNo}' => $order->getNumber()
//            ]));
//        $this->mailer->send($adminMessage);

//        } catch(\Exception $e) {
//        }
    }

    /**
     * @Route(pattern="/{id}/cancel", method="POST", secure="ROLE_USER,ROLE_ADMIN,ROLE_SUPER_ADMIN")
     * @param Application $app
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cancelAction(Application $app, $id)
    {
        $order = $this->orderRepository->find($id);

        if(null === $order) {
            $app->abort(404);
        }

        if(!$app['security']->isGranted([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN])) {
            $currentUser = $app->user();

            if(null === $currentUser || $currentUser->getId() !== $order->getCustomer()->getId()) {
                $app->abort(500);
            }
        }

        $order->setIsCanceled(true);
        $this->orderRepository->saveEntity($order);

        return $app->json();
    }

    /**
     * @Route(pattern="/{id}", method="GET", secure="ROLE_USER,ROLE_ADMIN,ROLE_SUPER_ADMIN")
     * @param Application $app
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(Application $app, $id)
    {
        $order = $this->orderRepository->find($id);

        if(null === $order) {
            $app->abort(404);
        }

        return $app->json(
            $this->converterService->convert($order, true)
        );
    }
}