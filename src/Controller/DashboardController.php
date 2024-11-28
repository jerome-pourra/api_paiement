<?php

namespace App\Controller;

use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{

    private StripeClient $gateway;

    public function __construct()
    {
        $this->gateway = new StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }

    #[Route('/checkout', name: 'app_dashboard_checkout', methods: ['POST'])]
    public function checkout(Request $request) {

        $amount = $request->request->get('amount');
        $mode = $request->request->get('mode');
        $quantity = $request->request->get('quantity');

        $session = $this->gateway->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => $quantity,
                ],
            ],
            'mode' => $mode,
            'success_url' => 'http://localhost:8080/success',
            'cancel_url' => 'http://localhost:8080/cancel',
        ]);
        return $this->redirect($session->url);
    }

    #[Route('/success', name: 'app_dashboard_success', methods: ['GET'])]
    public function success() {
        return $this->render('dashboard/success.html.twig');
    }

    #[Route('/cancel', name: 'app_dashboard_cancel', methods: ['GET'])]
    public function cancel() {
        return $this->render('dashboard/cancel.html.twig');
    }
}
