<?php

namespace App\Controller;

use Stripe\Webhook;
use App\Entity\Envoi;
use App\Entity\Resiliation;
use App\Manager\EnvoiManager;
use App\Services\Stripe\StripeApi;
use App\Repository\EnvoiRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripePaymentController extends AbstractController
{
    /**
     * @Route("/payment/{customId}", name="app_stripe_payment")
     */
    public function payment(
        Resiliation $resiliation,
        StripeApi $stripeApi,
        EnvoiRepository $envoiRepository
        ): Response
    {
        $envoi = $envoiRepository->getOneEnvoiByResiliation($resiliation->getCustomId());

        $response = $stripeApi->startPayment($envoi);

        return new RedirectResponse($response);
    }

    /**
     * @Route("/webhook/stripe", name="app_stripe_response")
     */
    public function stripeWebhookAction(
        Request $request,
        StripeApi $stripeApi
    ): Response
    {
        // $jsonStr = file_get_contents('php://input');
        // $event = json_decode($jsonStr);
        // dump($event);
        // dd($request);
        // $stripeApi->handle($request);

        $signature = $request->headers->get('stripe-signature');
        $body = $request->getContent();
        $event = Webhook::constructEvent(
            $body,
            $signature,
            $this->webhookSecret
        );
        dump($event);

        $type = $event->type;
        $object = $event->data->object;

        switch ($type) {
            case 'checkout.session.completed':
                dd($object);
                break;

            case 'payment_intent.succeeded ':
                dd($object);

                break;

            case 'payment_intent.created':
                dd($object);

                break;

            case 'checkout.session.completed':
                dd($object);

                break;

                // case 'checkout.session.completed':
                //     # code...
                //     break;

                // case 'checkout.session.completed':
                //     # code...
                //     break;

            default:
                echo 'Receive unknown event type '.$event->type;
        }

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * @Route("/stripe/success", name="app_stripe_success")
     */
    public function success(Request $request): Response
    {
        return $this->render('stripe_payment/index.html.twig', [
            'title' => 'Successful Payment',
            'state' => 'paid',
        ]);
    }

    /**
     * @Route("/stripe/cancel", name="app_stripe_cancel")
     */
    public function cancel(): Response
    {
        return $this->render('stripe_payment/index.html.twig', [
            'title' => 'Failed Payment',
            'state' => 'fail',
        ]);
    }
}
