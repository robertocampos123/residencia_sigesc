<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/usuario')]
class UsuarioController extends AbstractController
{
    #[Route('/', name: 'app_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        $user =  $this->getUser();
        return $this->render('usuario/index.html.twig', [
            //'usuarios' => $usuarioRepository->findAll(),
            'usuarios' => $usuarioRepository->findBy([],['email' => 'ASC']),
            'user' => $user,
            //$this->getDoctrine()->getRepository('MyBundle:MyTable')->findBy([], ['username' => 'ASC']);
        ]);
    }

    #[Route('/new', name: 'app_usuario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, UsuarioRepository $usuarioRepository): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $usuario->setPassword(
                $userPasswordHasher->hashPassword(
                $usuario,
                $form->get('password')->getData()
                )
            );

            $usuarioRepository->save($usuario, true);

            return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/change_password', name: 'app_usuario_change_password', methods: ['GET','POST'])]
    public function changePassword(Request $request,Usuario $usuario, UserPasswordHasherInterface $userPasswordHasher, UsuarioRepository $usuarioRepository): Response
    {
        if ($request->isMethod('POST')) {

            $new_password     = $_POST["newpassword"];
            $confirm_newpassword     = $_POST["confirmnewpassword"];
            
            if($new_password == $confirm_newpassword){

                $usuario->setPassword(
                    $userPasswordHasher->hashPassword(
                    $usuario,
                    $new_password));
    
                $usuarioRepository->save($usuario, true);
            
                return $this->redirectToRoute('app_home_docente', [], Response::HTTP_SEE_OTHER);   

            }else if($new_password != $confirm_newpassword){
               
                return new Response('Error, las contraseñas no coinciden, Intentelo Nuevamente');
                
            } 
        }
    }

    #[Route('/{id}/jefe_academico/change_password', name: 'app_jefeacademico_change_password', methods: ['GET','POST'])]
    public function changePasswordJF(Request $request,Usuario $usuario, UserPasswordHasherInterface $userPasswordHasher, UsuarioRepository $usuarioRepository): Response
    {
        if ($request->isMethod('POST')) {

            $new_password     = $_POST["newpassword"];
            $confirm_newpassword     = $_POST["confirmnewpassword"];
            
           if($new_password == $confirm_newpassword){

                $usuario->setPassword(
                    $userPasswordHasher->hashPassword(
                    $usuario,
                    $new_password)
                );
    
                $usuarioRepository->save($usuario, true);
            
                return $this->redirectToRoute('app_home_jefe_academico', [], Response::HTTP_SEE_OTHER);   

            }else if($new_password != $confirm_newpassword){

                return new Response('Error, las contraseñas no coinciden, Intentelo Nuevamente');
                
            }    
        }
    }

    #[Route('/{id}/admin/change_password', name: 'app_administrador_change_password', methods: ['GET','POST'])]
    public function changePasswordADM(Request $request,Usuario $usuario, UserPasswordHasherInterface $userPasswordHasher, UsuarioRepository $usuarioRepository): Response
    {
        if ($request->isMethod('POST')) {

            $new_password     = $_POST["newpassword"];
            $confirm_newpassword     = $_POST["confirmnewpassword"];
            
            if($new_password == $confirm_newpassword){

                $usuario->setPassword(
                    $userPasswordHasher->hashPassword(
                    $usuario,
                    $new_password)
                );
    
                $usuarioRepository->save($usuario, true);
            
                return $this->redirectToRoute('app_home_administrador', [], Response::HTTP_SEE_OTHER);   

            }else if($new_password != $confirm_newpassword){

                return new Response('Error, las contraseñas no coinciden, Intentelo Nuevamente');

            }
            
             
        }
    }

    #[Route('/{id}', name: 'app_usuario_show', methods: ['GET'])]
    public function show(Usuario $usuario): Response
    {
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuarioRepository->save($usuario, true);

            return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_usuario_delete', methods: ['POST'])]
    public function delete(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $usuarioRepository->remove($usuario, true);
        }

        return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
    }
}
