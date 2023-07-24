<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\Type\ProductType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->render('product/success.html.twig', [
                'message' => 'added'
            ]);
        }
        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/products/overview/{year}-{month}', name: 'overview')]
    public function overview(ManagerRegistry $doctrine,#[CurrentUser] User $user,int $month,int $year): Response
    {
        $commune = $user->getCommune();
        $years = $doctrine->getRepository(Product::class)->findByCommuneId($commune);
        $products = $doctrine->getRepository(Product::class)->findByYearMonth($commune,$month,$year);
        $users = $doctrine->getRepository(Product::class)->findByUserMonth($commune,$month,$year);
        $user_id = $user->getId();

        return $this->render('product/products.html.twig', [
            'products'=>$products,'years'=>$years,'users'=>$users, 'month'=>$month, 'year'=>$year, 'user_id'=>$user_id
        ]);
    }

    #[Route('/{_locale}/product/edit/{id}', name: 'product_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager,int $id): Response
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

            return $this->render('product/success.html.twig', [
                'message' => 'edited'
                ]);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form,
        ]);
        //TODO add here else with error messages for form
    }

    #[Route('/{_locale}/product/delete/{id}', name: 'product_delete')]
    public function deleteProduct( EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();
        return $this->render('product/success.html.twig', [
            'message' => 'deleted'
        ]);
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
