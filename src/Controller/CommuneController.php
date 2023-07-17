<?php

namespace App\Controller;

use App\Entity\AdminCommuneRegistration;
use App\Entity\Commune;
use App\Entity\User;


use App\Form\AdminCommuneRegistrationType;
use App\Form\CommuneType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommuneController extends AbstractController
{
    #[Route('/{_locale}/commune/new', name: 'new_commune')]
    public function new(Request $request,UserPasswordHasherInterface $userPasswordHasher, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager): Response
    {
        $commune_admin = new AdminCommuneRegistration();
        $commune = new Commune();
        $user = new User();

        $form = $this->createForm(AdminCommuneRegistrationType::class, $commune_admin, ['action' => $request->getRequestUri()]);
        $form->handleRequest($request);
        //print_r($form);
        if ($form->isSubmitted() /*&& $form->isValid()*/) {
            $commune->setName($commune_admin->commune_name);
            $user->setName($commune_admin->user_name);
            $user->setEmail($commune_admin->email);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(['ADMIN']);
            $user->setCommune($commune);
            $entityManager->persist($commune);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('new_commune_is_created');
        }
        return $this->render('commune/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{_locale}/new_commune_is_created', name: 'new_commune_is_created')]
    public function new_commune_is_created(Request $request): Response
    {
        return $this->render('commune/new_commune_is_created.html.twig',);
    }

    #[Route('/{_locale}/admin_panel', name: 'admin_panel')]
    public function admin_panel(ManagerRegistry $doctrine): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $commune = $user->getCommune();
            $roommates = $doctrine->getRepository(User::class)->findBy(
                ['commune_id' => $commune->getId()]
            );
            return $this->render('commune/admin_panel.html.twig',$roommates);
        }
        else {
            return $this->render('menu.html.twig',);
        }
    }

}