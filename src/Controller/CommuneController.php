<?php

namespace App\Controller;

use App\Entity\AdminCommuneRegistration;
use App\Entity\Commune;
use App\Entity\User;
use App\Entity\UserRepository;
use App\Form\AdminCommuneRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;
use function Symfony\Component\VarDumper\Dumper\esc;

class CommuneController extends AbstractController
{
    public function __construct( private Security $security) {}

    #[Route('/{_locale}/commune/new', name: 'new_commune')]
    public function new(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
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

    #[Route('/{_locale}/commune/admin_panel', name: 'admin_panel')]
    public function admin_panel(ManagerRegistry $doctrine): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $user = $this->getUser();
            $roommates = $doctrine->getRepository(User::class)->findBy(['commune'=>$user->getCommune()]);
            return $this->render('commune/admin_panel.html.twig',[
                'roommates'=>$roommates
            ]);
        }
        else {
            return $this->render('menu.html.twig',);
        }
    }

    #[Route('/{_locale}/commune/toggle_admin', name: 'toggle_admin')]
    public function toggle_admin(Request $request,ManagerRegistry $doctrine, EntityManagerInterface $entityManager):Response
    {
        $user_id = $request->get('id');
        $user = $doctrine->getRepository(User::class)->find($user_id);
        $roles = $user->getRoles();
        //dump($user);
        if($roles[0] == "ROLE_ADMIN") {
            $roles[0] = "ROLE_USER";
            echo 1;
        }
        else{
            $roles[0] = "ROLE_ADMIN";
            echo 2;
        }
        $user->setRoles($roles);
        $entityManager->persist($user);
        $entityManager->flush();

        //dump($user);
        //die();

    }

}