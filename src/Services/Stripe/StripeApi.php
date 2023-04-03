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
    const STRIPE_PAYMENT_SECRET_KEY = 'sk_test_51MKofmKp6gApFus8kcy3FoWI6d6mo8v7zPg9JY6L65ScWUnTE4bSQO7YMyuup3qH6aEgwUwedsoXheaUU6xLhmWe00QYTJWK4i'; 

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
                        'unit_amount' => 799
                    ],
                ]
            ],
            'success_url' => 'https://comment-resilier.info/lettre-de-resiliation-internet-mobile-tv/?status=success',
            'cancel_url' => 'https://comment-resilier.info/lettre-de-resiliation-internet-mobile-tv/?status=failed',
            'metadata' => [
                'envoi_id' => $envoi->getEnvoiId()
            ],
        ]);
        $envoi->setCustomData($sessionStripe->id);

        return $sessionStripe->url;
    }
}
