<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;

        $user = $this->getUser();
        
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();

            if ($passwordHasher->isPasswordValid($user, $old_password)) {
                $new_password = $form->get('new_password')->getData();
                $password = $passwordHasher->hashPassword($user, $new_password);

                $user->setPassword($password);
                $this->entityManager->flush(); // Execute la persistance et enregistre la en base de données 
                $notification = "✅ Votre mot de passe a bien été mis à jour";
            } else {
                $notification = "🚫 Votre mot de passe actuel n'est pas le bon ";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
