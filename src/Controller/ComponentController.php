<?php

namespace App\Controller;

use App\Entity\CallRequest;
use App\Entity\Param;
use App\Entity\Review;
use App\Form\CallRequestForm;
use App\Form\ReviewForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ComponentController extends Controller
{
    /**
     * Отзывы
     *
     * @Template("component/reviews.html.twig")
     */
    public function reviews()
    {
        $repository = $this->get('doctrine')->getRepository(Review::class);
        $reviews = $repository->getVisibleReviews(3);

        $params = $this->get('doctrine')->getRepository(Param::class);
        $reviewForm = $params->get('REVIEW_ADD_ENABLED') ?
            $this->createForm(ReviewForm::class, new Review())->createView() :
            null;

        return [
            'reviews'    => $reviews,
            'reviewForm' => $reviewForm,
        ];
    }

    /**
     * Форма запроса звонка
     *
     * @Template("component/call_request_form.html.twig")
     */
    public function callRequestForm()
    {
        $callRequestForm = $this->createForm(CallRequestForm::class, new CallRequest())->createView();

        return [
            'callRequestForm' => $callRequestForm,
        ];
    }
}
