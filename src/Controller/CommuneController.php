<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Product;
use App\Form\CommuneType;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommuneController extends AbstractController
{
    #[Route('/{_locale}/commune/new', name: 'new_commune')]
    public function new(Request $request, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager): Response
    {
        // creates a task object and initializes some data for this example
        $commune = new Commune();
        $commune->setName('Name');

        $form = $this->createForm(CommuneType::class, $commune, ['action' => $request->getRequestUri()]);
        $form->handleRequest($request);
        //print_r($form);
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $entityManager->persist($commune);
            $entityManager->flush();

            return $this->redirectToRoute('new_commune_is_created');
        }
        return $this->render('commune/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{_locale}/register_choose', name: 'register_choose')]
    public function register_choose(Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $commune = new Commune();

        $form = $this->createForm(CommuneType::class, $commune);

        return $this->render('registration/register_choose.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{_locale}/new_commune_is_created', name: 'new_commune_is_created')]
    public function new_commune_is_created(Request $request): Response
    {
        return $this->render('commune/new_commune_is_created.html.twig',);
    }

}