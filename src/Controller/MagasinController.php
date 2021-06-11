<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagasinController extends AbstractController
{
    #[Route('/magasin', name: 'app_homepage')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        // $em = $this->getDoctrine()->getManager();
        // $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        // $products = $em->getRepository(Product::class)->findAll();

        // $searchTerm = $request->get('search');
        // $resultProducts = $productRepository->search($searchTerm
        // );

        // if ($request->get('preview')) {
        //     return $this->render('magasin/_searchPreview.html.twig', [
        //         'resultProducts' => $resultProducts,
                
        //     ]);
        // }

        // return $this->render('magasin/index.html.twig', [
        //     'products' => $products,
        //     'user' => $user,
        //     'searchTerm' => $searchTerm
        // ]);

        $searchTerm = $request->get('search');
        $products = $productRepository->search($searchTerm
        );

        if ($request->get('preview')) {
            return $this->render('magasin/_searchPreview.html.twig', [
                'products' => $products,
            ]);
        }

        return $this->render('magasin/index.html.twig', [
            'products' => $products,
            'searchTerm' => $searchTerm
        ]);
    }

    #[Route('/magasin/category/{category}', name: 'magasin_Categorized')]
    public function magasin_Categorized(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $category = $request->get('category');
        $products = $em->getRepository(Product::class)->findby(['Category' => $category]);
        return $this->render('magasin/categorized.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }

    #[Route('/magasin/item/{product}', name: 'magasin_Item')]
    public function magasin_Item(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->get('product'));
        return $this->render('magasin/itemShow.html.twig', [
            'product' => $product,
        ]);
    }
}
