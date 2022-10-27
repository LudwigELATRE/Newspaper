<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
// use App\Controller\RegisterController;
use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: "register", methods: ["GET", "POST"])]
    public function register(Request $request, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());
            // Le ro^le nous permettra de distinguer un admin d'un user classique 
            $user->setRoles(["ROLE_USER"]);

            // On droit absolument hasher le password
            $user->setPassword(
                $passwordHasher->hashPassword($user, $user->getPassword())
            );
            // Cette ligne remplace $entityManager et fait un persist() + un fluch() (2nd param)
            $repository->save($user, true);

            $this->addFlash('success', 'Vous vous êtes inscrit avec success ');
            return $this->redirectToRoute(('app_login'));
        }
        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
