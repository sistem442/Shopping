<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\User;
use App\Entity\UserCommuneRegistration;
use App\Form\RegistrationFormType;
use App\Form\UserCommuneRegistrationType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class UserController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    #[Route('/{_locale}/admin_panel', name: 'admin_panel')]
    public function admin_panel(Request $request,ManagerRegistry $doctrine,  EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $users = $doctrine->getRepository(User::class)->findBy(['commune'=>$user->getCommune()]);
        return $this->render('/user/admin_panel.html.twig',['users'=>$users]);
    }
    #[Route('/{_locale}/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserCommuneRegistrationType::class,new UserCommuneRegistration());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setEmail($form->get('email')->getData());
            //get commune_id
            $commune_form = $form->get('communes');
            $commune = $commune_form->getData('modelData');
            $user->setCommune($commune);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
           /* $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('sistem442@gmail.com', 'Boris'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );*/
            // do anything else you need here, like send an email

            return $this->redirectToRoute('menu');
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    #[Route('/{_locale}/admin/delete/{email}', name: 'user_delete')]
    public function deleteUser( EntityManagerInterface $entityManager, string $email): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id ' . $email
            );
        }

        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin_panel');
    }
}