<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Entity\Product;
use App\Entity\ProductQuantity;
use App\Entity\Stripe;
use App\Entity\User;
use App\Service\CheckoutSession;
use Stripe\Checkout\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $quantities = $em->getRepository(ProductQuantity::class)->findBy(['Panier' => $user->getPanier()]);

        $user->getPanier()->setTotalPrice(0);
        $totalPrice = 0;
        foreach ($quantities as $quantity) {
            $totalPrice = $totalPrice + $quantity->getTotalQuantityPrice();
        }
        $user->getPanier()->setTotalPrice($user->getPanier()->getTotalPrice() + $totalPrice)->setDiscountUsed(false);
        $em->flush();

        return $this->render('panier/index.html.twig', [
            'quantities' => $quantities,
            'user' => $user,
        ]);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $quantities = $em->getRepository(ProductQuantity::class)->findBy(['Panier' => $user->getPanier()]);

        return $this->render('panier/checkout/checkout.html.twig', [
            'quantities' => $quantities,
            'user' => $user,
        ]);
    }

    #[Route('/checkout/session', name: 'set_checkout')]
    public function setCheckout(CheckoutSession $service)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $quantities = $em->getRepository(ProductQuantity::class)->findBy(['Panier' => $user->getPanier()]);

        $products = [];

        foreach ($quantities as $quantity) {
            $name = strval($quantity->getProduct()->getName());
            // $productQuantity = strval($quantity->getProduct()->getProductQuantities());
            $product = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $quantity->getProduct()->getPrice() * 100,
                    'product_data' => [
                        // 'name' => $name,
                        'name' => $quantity->getProduct()->getName(),
                    ],
                ],
                // 'quantity' => 2,
                'quantity' => $quantity->getQuantity(),
            ];
            array_push($products, $product);
        }
        $sucessUrl = $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $id = $service->createCheckoutSession($products, $sucessUrl);
        return new JsonResponse(['id' => $id]);
    }

    #[Route('/success', name: 'success')]
    public function sucess(Request $request, CheckoutSession $service): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $quantities = $em->getRepository(ProductQuantity::class)->findBy(['Panier' => $user->getPanier()]);
        $session = $service->getSessionById($request->get('session_id'));
        $paid = $session->payment_status;
        // dd($session);
        if ($session instanceof Session) {
            $stripe = new Stripe();
            $stripe->setSessionId($request->get('session_id'));
            if ($paid === 'paid') {
                $result = $this->render('panier/checkout/success.html.twig', []);
                $stripe->setPaymentStatus($paid)->setUser($user);
                foreach ($quantities as $quantity) {
                    $em->remove($quantity);
                }
            } else {
                $result = $this->render('panier/checkout/failed.html.twig', []);
                $stripe->setPaymentStatus($paid);
            }
            $em->persist($stripe);
            $em->flush();
        } else {
            throw $this->createNotFoundException('not_exist');
        }
        return $result;
    }

    #[Route('/cancel', name: 'cancel')]
    public function cancel(): Response
    {


        return $this->render('panier/checkout/cancel.html.twig', []);
    }

    #[Route('/add_to_cart', name: 'add_to_cart')]
    public function add_to_cart(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $product = $em->getRepository(Product::class)->find($request->get('product'));
        $quantity = $em->getRepository(ProductQuantity::class)->findOneByPanierIdAndProductId($user->getPanier(), $product->getId());

        if ($quantity === null) {
            $quantity = new ProductQuantity();
            $quantity->setPanier($user->getPanier())
                ->setProduct($product)
                ->setQuantity($request->get('_quantity'))
                ->setTotalQuantityPrice($product->getPrice() * $quantity->getQuantity());
            $em->persist($quantity);
        } else {
            $quantity->setQuantity($quantity->getQuantity() + $request->get('_quantity'))
                ->setTotalQuantityPrice($product->getPrice() * $quantity->getQuantity());
        }

        $em->flush();

        return $this->redirect('magasin/item/' . $product->getId());
    }

    #[Route('/adjust_cart/{product}', name: 'adjust_cart')]
    public function adjust_cart(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $product = $em->getRepository(Product::class)->find($request->get('product'));
        $quantity = $em->getRepository(ProductQuantity::class)->findOneByPanierIdAndProductId($user->getPanier(), $product->getId());

        $quantity->setQuantity($request->get('_quantity'));
        if ($quantity->getQuantity() === 0) {
            $em->remove($quantity);
        }

        $quantity->setTotalQuantityPrice($product->getPrice() * $quantity->getQuantity());

        $em->flush();

        return $this->redirectToRoute('panier');
    }

    #[Route('/remove_item_from_cart/{product}', name: 'remove_item_from_cart')]
    public function remove_item_from_cart(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $product = $em->getRepository(Product::class)->find($request->get('product'));
        $quantity = $em->getRepository(ProductQuantity::class)->findOneByPanierIdAndProductId($user->getPanier(), $product->getId());

        $quantity->setQuantity(0);
        $em->remove($quantity);
        $em->flush();

        return $this->redirectToRoute('panier');
    }

    #[Route('/discount', name: 'discount')]
    public function discount(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $discount = $em->getRepository(Discount::class)->findOneByDiscountCode($request->get('_discount'));

        $promo = '2';
        if ($discount && $discount->getUseCount() >= 1 && $user->getPanier()->getDiscountUsed() === false) {
            $user->getPanier()->setTotalPrice($user->getPanier()->getTotalPrice() - $user->getPanier()->getTotalPrice() * $discount->getDiscount() / 100)->setDiscountUsed(true);
            $discount->setUseCount($discount->getUseCount() - 1);
            $em->flush();
            $promo = '1';
        } elseif ($discount && $user->getPanier()->getDiscountUsed()) {
            $promo = '!';
        }
        return $this->redirectToRoute('checkout', ['promo' => $promo]);
    }

    #[Route('/history', name: 'history')]
    public function paymentHistory(Request $request, CheckoutSession $service): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $sessionIds = $user->getStripes();
        $stripe = new \Stripe\StripeClient("sk_test_51J3GoYLOoq2f2CBEBVfJ3PrAjm42go09ahfQ5BPK9zkqYtrqVPtLtzfge9LAGbFSX59UWZA04fhT4az56amubMBA00ME2BQzi0");

        $orders = [];
        foreach ($sessionIds as $sessionId) {
            $session = $service->getSessionById($sessionId->getSessionId());
            $pi = $session->payment_intent;
            $line_items = $stripe->checkout->sessions->allLineItems($sessionId->getSessionId(), ['limit' => 10]);
            $prices = $stripe->paymentIntents->retrieve($pi,[]);
            // dd($prices);
            $orderTicket = [$line_items, $prices];
            array_push($orders, $orderTicket);
        }
        // dd($orders);

        return $this->render('panier/history.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}
