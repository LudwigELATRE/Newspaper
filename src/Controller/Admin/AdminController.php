<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin')]
class AdminController extends AbstractController
{
    /*
    *
    */
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function showDashboard(): Response
    {
        # Pour vérifier si le User connecté à le rôle autorisée j'utiliser try/catch 
        try {
            # Si le rôle n'est pas bon, alors le script exécute le code du catch()
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        } catch (AccessDeniedException $exception) {
            # Ce bloc catch(attrape) les Exception lancée par la méthode dans le try
            # "cache" l'erreur et exécute le code après, ici un redirectToRoute()
            // dd($exception->getMessage() . 'dans le fichier' . __FILE__ . ' à la ligne' . __LINE__);
            return $this->redirectToRoute('show_home');
        } // End Catch

        return $this->render('admin/show_dashboard.html.twig');
    } // End showDashboard
}// End AdminController
