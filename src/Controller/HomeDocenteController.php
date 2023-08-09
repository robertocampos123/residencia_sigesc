<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Empleado;
use App\Entity\Periodo;
use App\Form\FirmaType;
use App\Form\FotografiaType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use App\Repository\EmpleadoRepository;
use App\Repository\GrupoRepository;
use App\Repository\ReporteFinalRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomeDocenteController extends AbstractController
{
    #[Route('/home/docente', name: 'app_home_docente')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        $cargo = $empleado->getCargo()->getClaveCargo();
        return $this->render('home_docente/index.html.twig', [
            'user' => $user,
            'cargo' => $cargo,
        ]);
    }

    #[Route('/mi_perfil', name: 'app_perfil_docente')]
    public function profile(ManagerRegistry $doctrine, GrupoRepository $grupoRepository): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        $num_grupos = $grupoRepository->numeroGrupos($empleado);
        
        return $this->render('home_docente/mi_perfil.html.twig', [
            'empleado' => $empleado,
            'no_grupos' => $num_grupos
        ]);
    }

    #[Route('/estadisticas/menu', name: 'app_estadisticas_docente_menu')]
    public function menuSeguimientos(ManagerRegistry $doctrine): Response
    {

        $repository_parcial = $doctrine->getRepository(Periodo::class);
        $seguimiento1 = $repository_parcial->findOneBy(['clave_periodo' => 'PARC1']);
        $seguimiento2 = $repository_parcial->findOneBy(['clave_periodo' => 'PARC2']);
        $seguimiento3 = $repository_parcial->findOneBy(['clave_periodo' => 'PARC3']);

        $imagenes = [
            "https://cdn-icons-png.flaticon.com/512/6912/6912872.png", 
            "https://cdn-icons-png.flaticon.com/512/6913/6913031.png", 
            "https://cdn-icons-png.flaticon.com/512/6913/6913185.png",
        ];
   
        return $this->render('grupos_docente/menu_seguimientos_docente.html.twig', [

            'seg1' => $seguimiento1,
            'seg2' => $seguimiento2,
            'seg3' => $seguimiento3,
            'imagen'     => $imagenes,
           
        ]);
    }

    #[Route('/recursos', name: 'app_recursos')]
    public function verRecursos(ManagerRegistry $doctrine, ReporteFinalRepository $rf): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $documentos = $rf->findBy(['empleado'=>$empleado]);
   
        return $this->render('home_docente/mis_recursos.html.twig', [
            'documentos' => $documentos,
     
        ]);
    }

    #[Route('/docente/cambiar_password', name: 'cambiar_password_docente')]
    public function cambiarPassword(): Response
    {

        $user = $this->getUser();
        return $this->render('usuario/cambiar_password.html.twig', [
            'user' => $user,
            
        ]);
    }

    #[Route('empleado/{id}/fotografias', name: 'app_empleado_cambiar_fotografia',methods: ['GET', 'POST'])]
    public function import(Empleado $empleado, ManagerRegistry $doctrine, HttpFoundationRequest $request, EmpleadoRepository $empleadoRepository): Response

    {
        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        

        $firma = $empleado->getFirmaEmpleado();
        $fotografia = $empleado->getFotografiaEmpleado();
        $rfc = $empleado->getRFC();
        
        $form1 = $this->createForm(FirmaType::class, $empleado);
        $form1->handleRequest($request);

        $form2 = $this->createForm(FotografiaType::class, $empleado);
        $form2->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()) {

            $archivo_firma = $form1->get('firma_empleado')->getData();

            if ($archivo_firma) {

                if($firma){

                    $filesystem = new Filesystem();
                    $path=$this->getParameter("firma_directory").'/'.$firma;
                    $filesystem->remove($path);
    
                }
                $newFilenameFir = 'firma'.'-'.$rfc.'.'.$archivo_firma->guessExtension();
                try {
                    $archivo_firma->move(
                        $this->getParameter('firma_directory'),
                        $newFilenameFir
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                
                $empleado->setFirmaEmpleado($newFilenameFir);
                $empleadoRepository->save($empleado, true);

                //{{ path('app_carrera_delete', {'id': carrera.id}) }}

                return $this->redirectToRoute('app_perfil_docente', [], Response::HTTP_SEE_OTHER);
            }
            
        }

        if ($form2->isSubmitted() && $form2->isValid()) {

            $archivo_fotografia = $form2->get('fotografia_empleado')->getData();

            if ($archivo_fotografia) {

                if($fotografia){

                    $filesystem = new Filesystem();
                    $path=$this->getParameter("fotografia_directory").'/'.$fotografia;
                    $filesystem->remove($path);
    
                }
                $newFilename = $fotografia.'-'.$rfc.'.'.$archivo_fotografia->guessExtension();
                try {
                    $archivo_fotografia->move(
                        $this->getParameter('fotografia_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                
                $empleado->setFotografiaEmpleado($newFilename);
                $empleadoRepository->save($empleado, true);

                return $this->redirectToRoute('app_perfil_docente', [], Response::HTTP_SEE_OTHER);
            }
            
        }

        return $this->renderForm('home_docente/mis_imagenes.html.twig',[
            'form1'  => $form1,
            'form2'  => $form2,
            'user' => $user,
            'firma' => $firma,
            'fotografia' => $fotografia,
        ]);
         

    }


}
