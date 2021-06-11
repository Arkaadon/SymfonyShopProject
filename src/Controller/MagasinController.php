<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class MagasinController extends AbstractController
{
    #[Route('/magasin', name: 'app_homepage')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $productsCheck = $em->getRepository(Product::class)->findAll();
        $discountsCheck = $em->getRepository(Discount::class)->findAll();

        if (!$productsCheck && !$discountsCheck) {
            return $this->redirectToRoute('dbInitialize');
        }

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

    #[Route('/magasin/dbInitialize', name: 'dbInitialize')]
    public function dbInitialize(): Response
    {
        

        return $this->render('magasin/databaseEmpty.html.twig', [
           
        ]);
    }

    public function Initialize($kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'CreateFixtures'
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        // return new Response(""), if you used NullOutput()
        return new Response($content);
    }

    #[Route('/magasin/dbInitialize/true', name: 'dbInitializeTrue')]
    public function dbInitializeTrue(KernelInterface $kernel): Response
    {
        echo $this->Initialize($kernel);

        return $this->redirectToRoute('app_homepage');
        // return $this->render('magasin/databaseEmptyTrue.html.twig', [
            
        // ]);
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
