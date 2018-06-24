<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Page;
use App\Entity\Param;
use App\Entity\Promocode;
use App\Entity\Service;
use App\Event\OrderCreatedEvent;
use App\Form\OrderForm1;
use App\Form\OrderForm2;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("default/index.html.twig")
     */
    public function index(Request $request)
    {
        // Значения из сессии/по-умолчанию
        $session = $this->get('session');
        $data    = $session->get('order', []);

        $form = $this->getOrderForm1($data);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('order', $form->getData());

            return $this->redirectToRoute('order');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Форма заказа
     *
     * @Route("/order", name="order")
     * @Template("default/order.html.twig")
     */
    public function order(Request $request)
    {
        // Проверка данных, введенных в первой форме
        $session = $this->get('session');
        $data    = $session->get('order', []);

        $form = $this->getOrderForm1([], ['csrf_protection' => false]);
        $form->submit($data);

        // Если проверка не пройдена переход на главную
        if (!$form->isValid()) {
            $session->remove('order');

            return $this->redirectToRoute('homepage');
        }

        $serviceRepository = $this->get('doctrine')->getRepository(Service::class);
        $paramRepository   = $this->get('doctrine')->getRepository(Param::class);

        /**
         * Количество комнат и санузлов
         * @var Service $serviceRoom
         * @var Service $serviceBathroom
         */
        $serviceRoom     = $serviceRepository->getServiceRoom();
        $serviceBathroom = $serviceRepository->getServiceBathroom();
        $countRoom       = $data['service_'.$serviceRoom->getId()];
        $countBathroom   = $data['service_'.$serviceBathroom->getId()];
        // Дата уборки
        $datetime = $data['datetime'];

        // Дополнительные услуги
        $services = $serviceRepository->getAdditionalServices();

        $form = $this->createForm(OrderForm2::class, $data, ['services' => $services]);
        $form->handleRequest($request);

        // Промокод
        $promocode = null;
        if (!empty($form->getData()['promocode'])) {
            $promocode = $this->get('doctrine')->getRepository(Promocode::class)
                ->getActivePromocode($form->getData()['promocode']);
        }

        $message = null;
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Сохранение заказа
                $this->createOrder($form->getData());
                // Сообщение
                $message = $paramRepository->get('ORDER_ADD_SUCCESS_MESSAGE');
            } catch (\Exception $e) {
                // Сообщение
                $message = $paramRepository->get('ORDER_ADD_ERROR_MESSAGE');
            } finally {
                // Удаление данных из сессии
                $session->remove('order');
            }
        }

        return [
            'services'        => $services,
            'form'            => $form->createView(),
            'countRoom'       => $countRoom,
            'countBathroom'   => $countBathroom,
            'datetime'        => $datetime,
            'serviceRoom'     => $serviceRoom,
            'serviceBathroom' => $serviceBathroom,
            'promocode'       => $promocode,
            'message'         => $message,
        ];
    }

    /**
     * Текстовая страница
     *
     * @Route("/page/{slug}", name="page")
     * @Template("default/page.html.twig")
     */
    public function pageAction($slug)
    {
        $repository = $this->get('doctrine')->getRepository(Page::class);
        $page       = $repository->getPage($slug);

        if (null === $page) {
            throw new NotFoundHttpException();
        }

        return [
            'page' => $page,
        ];
    }

    /**
     * Форма заказа - шаг 1
     *
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getOrderForm1(array $data = [], $options = [])
    {
        // Услуги
        $repository = $this->get('doctrine')->getRepository(Service::class);
        $services   = $repository->getBasicServices();

        // Настройки
        $params = $this->get('doctrine')->getRepository(Param::class);

        // Параметры формы
        $defaultOptions = [
            'services'             => $services,
            'min_time'             => $params->get('ORDER_MIN_TIME'),
            'max_time'             => $params->get('ORDER_MAX_TIME'),
            'time_step'            => (int)$params->get('ORDER_TIME_STEP'),
            'min_time_to_cleaning' => (int)$params->get('ORDER_MIN_TIME_TO_CLEANING'),
            'max_time_to_cleaning' => (int)$params->get('ORDER_MAX_TIME_TO_CLEANING'),
        ];

        $options = array_merge($defaultOptions, $options);

        return $this->createForm(OrderForm1::class, $data, $options);
    }


    /**
     * Сохранение заказа
     *
     * @param $data
     * @throws \Exception
     *
     * @return Order
     */
    protected function createOrder($data)
    {
        $em                  = $this->get('doctrine')->getManager();
        $serviceRepository   = $this->get('doctrine')->getRepository(Service::class);
        $promocodeRepository = $this->get('doctrine')->getRepository(Promocode::class);
        $paramRepository     = $this->get('doctrine')->getRepository(Param::class);

        $order = (new Order())
            ->setDatetime($data['datetime'])
            // Контакты
            ->setName($data['name'])
            ->setPhone($data['phone'])
            // Адрес
            ->setCity($data['city'])
            ->setStreet($data['street'])
            ->setHome($data['home'])
            ->setBuilding($data['building'])
            ->setFlat($data['flat'])
            // Комментарий
            ->setComment($data['comment']);

        // Базовая стоимость
        $order->setBaseCost($paramRepository->get('ORDER_BASE_COST'));

        // Услуги
        foreach ($data as $key => $quantity) {
            // Если кол-во = 0, услуга не заказана
            if (!$quantity) continue;

            if (preg_match('/^service_(\d+)/ui', $key, $matches)) {
                $serviceId = $matches[1];
                $service   = $serviceRepository->getAvailableById($serviceId);
                if (!$service instanceof Service) {
                    throw new \Exception('Service #'.$serviceId.' not found.');
                }

                // Добавление услуги к заказу
                $order->addService($service, $quantity);
            }
        }

        // Промокод
        if (!empty($data['promocode'])) {
            $promocode = $promocodeRepository->getActivePromocode($data['promocode']);
            if (!$promocode instanceof Promocode) {
                throw new \Exception('Promocode not found.');
            }

            $order->setPromocode($promocode);
            $order->setDiscountPromocode($promocode->getDiscount());
        }

        // Периодичность
        $order->setFrequency($data['frequency']);
        if ($data['frequency'] != Order::FREQUENCY_ONCE) {
            // Скидка
            $code = 'FREQUENCY_DISCOUNT_'.$data['frequency'];
            $discount = $paramRepository->get($code);
            $order->setDiscountFrequency((int)$discount);
        }

        $em->persist($order);
        $em->flush();

        // Событие создания заказа через сайт
        $event = new OrderCreatedEvent($order);
        $this->get('event_dispatcher')->dispatch(OrderCreatedEvent::NAME, $event);

        return $order;
    }
}
