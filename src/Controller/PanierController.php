<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Entity\Product;
use App\Entity\ProductQuantity;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/checkout/{promo}', name: 'checkout')]
    public function checkout(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $quantities = $em->getRepository(ProductQuantity::class)->findBy(['Panier' => $user->getPanier()]);

        return $this->render('panier/checkout.html.twig', [
            'quantities' => $quantities,
            'user' => $user,
        ]);
    }

    #[Route('/pay', name: 'pay')]
    public function pay(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $quantities = $em->getRepository(ProductQuantity::class)->findBy(['Panier' => $user->getPanier()]);

        foreach ($quantities as $quantity) {
            $quantity->getPanier()->setTotalPrice($quantity->getPanier()->getTotalPrice() - $quantity->getTotalQuantityPrice());
            $em->remove($quantity);
        };

        $em->flush();

        return $this->render('panier/pay.html.twig', [
            'user' => $user,
        ]);
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
            $discount->setUseCount($discount->getUseCount()-1);
            $em->flush();
            $promo = '1';
        } elseif ($discount && $user->getPanier()->getDiscountUsed()) {
            $promo = '!';
        }
        return $this->redirectToRoute('checkout', ['promo' => $promo]);
    }
}
