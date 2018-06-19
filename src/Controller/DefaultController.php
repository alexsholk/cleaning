<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Param;
use App\Entity\Service;
use App\Form\OrderForm1;
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
        return [];
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
}
