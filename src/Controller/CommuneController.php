<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Product;
use App\Form\RegisterChooseType;
use App\Form\Type\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommuneController extends AbstractController
{
    public function new(Request $request, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager): Response
    {
        // creates a task object and initializes some data for this example
        $commune = new Product();
        $commune->setName('Name');
        $commune->setDescription('Description');

        return $this;
    }
    #[Route('/{_locale}/register_choose', name: 'register_choose')]
    public function register_choose(Request $request): Response
    {
        // creates a task object and initializes some data for this example
        $commune = new Commune();

        $form = $this->createForm(RegisterChooseType::class, $commune);

        return $this->render('registration/register_choose.html.twig', [
            'form' => $form,
        ]);
    }
}