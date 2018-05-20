<?php

namespace App\Controller;

use App\Entity\CallRequest;
use App\Entity\Promocode;
use App\Entity\Review;
use App\Entity\Session;
use App\Entity\Street;
use App\Form\CallRequestForm;
use App\Form\ReviewForm;
use Darsyn\IP\IP;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AjaxController extends Controller
{
    /**
     * Запрос звонка (AJAX)
     *
     * @Route("/call-request", name="call_request", condition="request.isXmlHttpRequest()")
     */
    public function callRequest(Request $request)
    {
        $callRequestForm = $this->createForm(CallRequestForm::class, new CallRequest(), [
            'validation_groups' => ['Default', 'anti_spam'],
        ]);

        $callRequestForm->handleRequest($request);

        $data = [];
        if ($callRequestForm->isSubmitted()) {
            if ($callRequestForm->isValid()) {
                try {
                    /** @var CallRequest $callRequest */
                    $callRequest = $callRequestForm->getData();
                    $callRequest->setIp(new IP($request->getClientIp()));

                    $em = $this->get('doctrine')->getManager();
                    $em->persist($callRequest);
                    $em->flush();

                    /** apptodo СМС-уведомление */

                    $data['status'] = 'success';
                } catch (\Exception $e) {
                    $data['status'] = 'error';
                }
            } else {
                $data['status'] = 'error';
                foreach ($callRequestForm->getErrors(true) as $error) {
                    $data['errors'][$error->getOrigin()->getName()][] = $error->getMessage();
                }
            }
        }

        return new JsonResponse($data);
    }

    /**
     * Добавление отзыва (AJAX)
     *
     * @Route("/add-review", name="add_review", condition="request.isXmlHttpRequest()")
     */
    public function addReview(Request $request)
    {
        $reviewForm = $this->createForm(ReviewForm::class, new Review(), [
            'validation_groups' => ['Default', 'anti_spam'],
        ]);

        $reviewForm->handleRequest($request);

        $data = [];
        if ($reviewForm->isSubmitted()) {
            if ($reviewForm->isValid()) {
                try {
                    /** @var Review $review */
                    $review = $reviewForm->getData();
                    $review->setVisible(false)
                        ->setIp(new IP($request->getClientIp()));

                    $em = $this->get('doctrine')->getManager();
                    $em->persist($review);
                    $em->flush();

                    $data['status'] = 'success';
                } catch (\Exception $e) {
                    $data['status'] = 'error';
                }
            } else {
                $data['status'] = 'error';
                foreach ($reviewForm->getErrors(true) as $error) {
                    $data['errors'][$error->getOrigin()->getName()][] = $error->getMessage();
                }
            }
        }

        return new JsonResponse($data);
    }

    /**
     * Поиск улиц для автозаполнения
     *
     * @Route("/get-streets", name="get_streets", condition="request.isXmlHttpRequest()")
     */
    public function getStreets(Request $request)
    {
        $query = $request->get('query');

        $repository = $this->get('doctrine')->getRepository(Street::class);
        $streets = $repository->searchStreet($query, 6);

        $data = [
            'query'       => $query,
            'suggestions' => $streets,
        ];

        return new JsonResponse($data);
    }

    /**
     * Проверка промокода (AJAX)
     *
     * @Route("/check-promocode", name="check_promocode", condition="request.isXmlHttpRequest()")
     */
    public function checkPromocode(Request $request)
    {
        // Ответ
        $response = [
            'status'   => 'error',
            'message'  => null,
            'discount' => 0,
        ];

        if (!$code = $request->get('promocode')) {
            return new JsonResponse($response);
        }

        // Поиск промокода
        $repository = $this->get('doctrine')->getRepository(Promocode::class);
        /** @var Promocode $promocode */
        $promocode = $repository->getEnabledPromocode($code);

        // Текущее время и дата
        $now = new \DateTime();

        if (is_null($promocode)) {
            $response['message'] = 'Промокод не найден';
        } elseif (!is_null($promocode->getStartDate()) && $promocode->getStartDate() > $now) {
            $response['message'] = 'Промокод начнет действовать с '.$promocode->getStartDate()->format('d.m.Y');
        } elseif (!is_null($promocode->getEndDate()) && $promocode->getEndDate() < $now) {
            $response['message'] = 'Срок действия промокода истек';
        } else {
            // Количество использованных промокодов (привязанных к заказам)
            $activatedCount = $repository->getActivatedCount($code);

            // Проверка зарезервированных другими клиентами промокодов
            $session = $this->get('session');
            $repository = $this->get('doctrine')->getRepository(Session::class);
            // Активные сессии помимо текущей
            $activeSessions = $repository->getActiveSessions($session->getId());
            $reservedCount = 0;
            foreach ($activeSessions as $activeSession) {
                /** @var Session $activeSession */
                $order = $activeSession->get('order');
                if (isset($order['promocode']) && $order['promocode'] == $code) {
                    $reservedCount++;
                }
            }

            // Количество доступных промокодов
            $availableCount = $promocode->getQuantity() - $activatedCount - $reservedCount;

            // Если есть доступные промокоды
            if ($availableCount > 0) {
                $order                = $session->get('order', []);
                $order['promocode']   = $code;
                $session->set('order', $order);
                $response['status']   = 'success';
                $response['message']  = 'Промокод активирован';
                $response['discount'] = $promocode->getDiscount();
            } else {
                $response['message'] = 'Скидка по этому промокоду больше не предоставляется';
            }
        }

        return new JsonResponse($response);
    }

    /**
     * Очистка промокода (AJAX)
     *
     * @Route("/clear-promocode", name="clear_promocode", condition="request.isXmlHttpRequest()")
     */
    public function clearPromocode(Request $request)
    {
        $session = $this->get('session');
        $order   = $session->get('order', []);

        unset($order['promocode']);
        $session->set('order', $order);

        $response = [
            'status'   => 'success',
            'discount' => 0,
        ];

        return new JsonResponse($response);
    }
}
