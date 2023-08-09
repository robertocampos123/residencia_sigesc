<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeAdministradorController extends AbstractController
{
    #[Route('/home/administrador', name: 'app_home_administrador')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('home_administrador/index.html.twig', [
            'controller_name' => 'HomeAdministrador',
            'user' => $user,
        ]);
    }

    #[Route('/admin/cambiar_password', name: 'cambiar_password_admin')]
    public function cambiarPasswordAdmin(): Response
    {
        $user = $this->getUser();
        return $this->render('usuario/cambiar_password_admin.html.twig', [
            'user' => $user,
            
        ]);
    }
}
