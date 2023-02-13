<?php

namespace App\Controller;

use App\Entity\Resiliation;
use App\Manager\StripeTransactionManager;
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
    ): Response {
        $envoi = $envoiRepository->getOneEnvoiByResiliation($resiliation->getCustomId());

        $response = $stripeApi->startPayment($envoi);

        return new RedirectResponse($response);
    }

    /**
     * @Route("/webhook/stripe", name="app_stripe_response")
     */
    public function stripeWebhookAction(StripeTransactionManager $stripeTransactionManager): Response
    {
        $endpoint_secret = $_ENV['STRIPE_WEBHOOK_SECRET_KEY'];
        $payload = file_get_contents('php://input');
        $event = json_decode($payload);
        \Stripe\Stripe::setApiKey($endpoint_secret);

        switch ($event->type) {
            case 'charge.succeeded':
                $charge = $event->data->object;
                echo 'Received unknown event type ' . $event->type;
                
            case 'payment_intent.created':
                $paymentIntent = $event->data->object;
                $transaction = $stripeTransactionManager->init();
                $transaction->setIntentId($paymentIntent->id);
                $transaction->setAmount($paymentIntent->amount);
                $transaction->setAmountReceived($paymentIntent->amount_received);
                $transaction->setConfirmationMethod($paymentIntent->confirmation_method);
                $transaction->setCreated($paymentIntent->created);
                $transaction->setCurrency($paymentIntent->currency);
                $transaction->setClientSecret($paymentIntent->client_secret);
                $transaction->setClientSecret($paymentIntent->client_secret);
                $transaction->setIntentId($paymentIntent->id);
                $transaction->setIntentId($paymentIntent->id);
                $stripeTransactionManager->save($transaction);
                echo 'Received unknown event type ' . $event->type;
                
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                echo 'Received unknown event type ' . $event->type;
                
            case 'checkout.session.completed':
                $session = $event->data->object;
                echo 'Received unknown event type ' . $event->type;
                
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * @Route("/stripe/success", name="app_stripe_success")
     */
    public function success(Request $request): Response
    {
        return $this->render('stripe_payment/index.html.twig', []);
    }

    /**
     * @Route("/stripe/cancel", name="app_stripe_cancel")
     */
    public function cancel(): Response
    {
        return $this->render('stripe_payment/cancel.html.twig', []);
    }
}
