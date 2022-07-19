<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User(); // J'instancie ma classe User(), j'ai donc un nouvel objet User...
        
        $form = $this->createForm(RegisterType::class, $user); // J'instancie mon formulaire avec la méthode createForm... (en paramètres : la classe du formulaire, et l'objet User )

        $form->handleRequest($request); // Écoute la requête entrante

        // Si le formulaire est soumis et qu'il est valide par rapport aux contraintes que l'on a rentré dans notre RegisterType.php
        if ($form->isSubmitted() && $form->isValid()) {

            // Injecte dans mon objet User() toutes les données qui sont récupérées du formulaire
            $user = $form->getData();

            // J'utilise UserPasswordHasherInterface pour encoder le mot de passe
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            // Je réinjecte $password qui est crypté dans l'objet User()
            $user->setPassword($password);

            // Je fige la data avant de l'enregistrer
            $this->entityManager->persist($user);
            // Execute la persistance et enregistre la en base de données
            $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView() // Je passe le formulaire en variable à mon template avec createView
        ]);
    }
}
