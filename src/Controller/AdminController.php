<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
        ]);

    }

    #[Route('/admin/user_management', name: 'user_management')]
    public function userManagement(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('admin/user_management.html.twig', [
            'users' => $users
        ]);

    }

    #[Route('/admin/user_management/edit/{user}', name: 'user_edit')]
    public function userEdit(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('user'));

        return $this->render('admin/edit_user.html.twig', [
            'user' => $user
        ]);

    }

    #[Route('/admin/user_management/edit/completed/{user}', name: 'edit_user_completed')]
    public function editUserCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('user'));

        $user->setUsername($request->get('_username'))->setRoles(array_unique([$request->get('_role')]));
        $em->flush();

        return $this->redirectToRoute('user_edit', ['user' => $user->getId()]);

    }

    #[Route('/admin/user_management/edit/removed/{user}', name: 'remove_user_completed')]
    public function removeUserCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('user'));

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_list');

    }

    #[Route('/admin/product', name: 'product_list')]
    public function product(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();


        return $this->render('admin/list_product.html.twig', [
            'products' => $products
        ]);

    }

    #[Route('/admin/product/create', name: 'create_product')]
    public function createProduct(Request $request): Response
    {

        return $this->render('admin/create_product.html.twig', [
            
        ]);

    }

    #[Route('/admin/product/create/completed/', name: 'create_product_completed')]
    public function createProductCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = new Product();

        $product->setCategory($request->get('_category'))->setName($request->get('_name'))->setDescription($request->get('_description'))->setPrice($request->get('_price'));
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('create_product');

    }

    #[Route('/admin/product/edit/{product}', name: 'edit_product')]
    public function editProduct(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->get('product'));


        return $this->render('admin/edit_product.html.twig', [
            'product' => $product
        ]);

    }

    #[Route('/admin/product/edit/completed/{product}', name: 'edit_product_completed')]
    public function editProductCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->get('product'));

        $product->setCategory($request->get('_category'))->setName($request->get('_name'))->setDescription($request->get('_description'))->setPrice($request->get('_price'));
        $em->flush();

        return $this->redirectToRoute('edit_product', ['product' => $product->getId()]);

    }

    #[Route('/admin/product/edit/removed/{product}', name: 'remove_product_completed')]
    public function removeProductCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->get('product'));

        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('product_list');

    }

    #[Route('/admin/discount', name: 'discount_list')]
    public function discount(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $discounts = $em->getRepository(Discount::class)->findAll();


        return $this->render('admin/list_discount.html.twig', [
            'discounts' => $discounts
        ]);

    }

    #[Route('/admin/discount/create', name: 'create_discount')]
    public function createDiscount(Request $request): Response
    {

        return $this->render('admin/create_discount.html.twig', [
            
        ]);

    }

    #[Route('/admin/discount/create/completed/', name: 'create_discount_completed')]
    public function createDiscountCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $discount = new Discount();

        $discount->setDiscountCode($request->get('_code'))->setDiscount($request->get('_discount'))->setUseCount($request->get('_use-count'));
        $em->persist($discount);
        $em->flush();

        return $this->redirectToRoute('create_discount');

    }

    #[Route('/admin/discount/edit/{discount}', name: 'edit_discount')]
    public function editDiscount(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $discount = $em->getRepository(Discount::class)->find($request->get('discount'));


        return $this->render('admin/edit_discount.html.twig', [
            'discount' => $discount
        ]);

    }

    #[Route('/admin/discount/edit/completed/{discount}', name: 'edit_discount_completed')]
    public function editDiscountCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $discount = $em->getRepository(Discount::class)->find($request->get('discount'));

        $discount->setDiscountCode($request->get('_code'))->setDiscount($request->get('_discount'))->setUseCount($request->get('_use-count'));
        $em->flush();

        return $this->redirectToRoute('edit_discount', ['discount' => $discount->getId()]);

    }

    #[Route('/admin/discount/edit/removed/{discount}', name: 'remove_discount_completed')]
    public function removeDiscountCompleted(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $discount = $em->getRepository(Discount::class)->find($request->get('discount'));

        $em->remove($discount);
        $em->flush();

        return $this->redirectToRoute('discount_list');

    }
}
