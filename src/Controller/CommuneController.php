<?php

namespace App\Controller;

use App\Entity\AdminCommuneRegistration;
use App\Entity\Commune;
use App\Entity\User;


use App\Form\AdminCommuneRegistrationType;
use App\Form\CommuneType;
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
        $commune_admin = new AdminCommuneRegistration();
        $commune = new Commune();
        $user = new User();

        $form = $this->createForm(AdminCommuneRegistrationType::class, $commune_admin, ['action' => $request->getRequestUri()]);
        $form->handleRequest($request);
        //print_r($form);
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $commune->setName($commune_admin->name);
            $user->setEmail($commune_admin->email);
            $user->setPassword($commune_admin->plainPassword);
            $user->setRoles(['ADMIN']);
            $entityManager->persist($commune);
            $entityManager->flush();
            $user->setCommune($commune);
            $entityManager->persist($user);
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

        return $this->render('user/register_choose.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{_locale}/new_commune_is_created', name: 'new_commune_is_created')]
    public function new_commune_is_created(Request $request): Response
    {
        return $this->render('commune/new_commune_is_created.html.twig',);
    }

}