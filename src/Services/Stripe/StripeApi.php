<?php

namespace App\Services\Stripe;

use Stripe\Stripe;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{ApiExchange, Envoi, Resiliation};
use App\Manager\EnvoiManager;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StripeApi
{

    public function __construct(string $secretKey, string $webhookSecret = '')
    {
        $this->secretKey = $secretKey;
        $this->webhookSecret = $webhookSecret;
        Stripe::setApiKey($secretKey);
        Stripe::setApiVersion('2022-11-15');
    }

    public function startPayment(Envoi $envoi)
    {
        $sessionStripe = Session::create([
            'mode' => 'payment',
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => 'EUR',
                        'product_data' => [
                            'name' => $envoi->getName()
                        ],
                        'unit_amount' => 300
                    ],
                ]
            ],
            'success_url' => 'https://127.0.0.1:8000/stripe/success',
            'cancel_url' => 'https://127.0.0.1:8000/stripe/failed',
            'metadata' => [
                'envoi_id' => $envoi->getEnvoiId()
            ],
        ]);
        $envoi->setCustomData($sessionStripe->id);

        return $sessionStripe->url;
    }

    public function handle(Request $request)
    {
        $signature = $request->headers->get('stripe-signature');
        $body = $request->getContent();
        $event = Webhook::constructEvent(
            $body,
            $signature,
            $this->webhookSecret
        );

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
    }
}
