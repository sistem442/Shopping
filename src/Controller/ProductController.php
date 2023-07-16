<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\Type\ProductType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProductController extends AbstractController
{
    #[Route('/{_locale}/product/add', name: 'create_product')]
    public function new(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $product = new Product();
        $product->setName('Name');
        $product->setDescription('Description');
        $product->setPrice('100');
        $product->setPurchasedAt(new DateTime('today'));
        $product->setUser($user);

        $form = $this->createForm(ProductType::class, $product, ['action' => $request->getRequestUri()]);
        $form->handleRequest($request);
        //print_r($form);
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products_paginated', ['page' => 1]);
        }
        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/products/page/{page<[1-9]\d*>}', defaults: ['_format' => 'html'], name: 'products_paginated')]
    public function findAll(ManagerRegistry $doctrine, int $page): Response
    {
        $products = $doctrine->getRepository(Product::class)->findAll($page);
        return $this->render('product/products.html.twig', [
            'paginator' => $products
        ]);
    }


    #[Route('/{_locale}/product/{id}', name: 'product_show')]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$product->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }

    #[Route('/{_locale}/product/edit/{id}', name: 'product_edit')]
    public function edit(Request $request, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager,int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $form = $this->createForm(ProductType::class, $product, ['action' => $request->getRequestUri()]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products_paginated', ['page' => 1]);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
        //TODO add here else with error messages for form
    }

    #[Route('/{_locale}/product/delete/{id}', name: 'product_delete')]
    public function deleteProduct(ManagerRegistry $doctrine, Request $request, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('products_paginated',['page'=>1]);
    }

    #[Route('/{_locale}/menu', name: 'menu')]
    public function menu(): Response
    {
        return $this->render('menuMain.html.twig');
    }

    #[Route('/products/test', name: 'test')]
    public function test(): Response
    {
        return $this->render('base.html.twig');
    }

}
