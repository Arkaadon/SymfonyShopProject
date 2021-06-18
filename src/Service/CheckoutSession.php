<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\Routing\Annotation\Route;
\Stripe\Stripe::setApiKey('sk_test_51J3GoYLOoq2f2CBEBVfJ3PrAjm42go09ahfQ5BPK9zkqYtrqVPtLtzfge9LAGbFSX59UWZA04fhT4az56amubMBA00ME2BQzi0');

class CheckoutSession
{
  public function createCheckoutSession($products, $sucessUrl)
  {
    $checkout_session = Session::create([
      'payment_method_types' => ['card'],
      'line_items' => $products,
      'mode' => 'payment',
      'success_url' => $sucessUrl.'?session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => 'https://127.0.0.1:8000/cancel',
    ]);

    return $checkout_session->id;
  }

  public function getSessionById($sessionId)
  {

    $stripe = new StripeClient('sk_test_51J3GoYLOoq2f2CBEBVfJ3PrAjm42go09ahfQ5BPK9zkqYtrqVPtLtzfge9LAGbFSX59UWZA04fhT4az56amubMBA00ME2BQzi0');
    try{
      return $stripe->checkout->sessions->retrieve($sessionId);
    }catch(ApiErrorException $e){

    }
  }
}
