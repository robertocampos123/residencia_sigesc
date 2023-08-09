<?php

namespace App\Controller;

use App\Entity\Empleado;
use App\Entity\Grupo;
use App\Entity\Periodo;
use App\Entity\ReporteFinal;
use App\Entity\Seguimiento;
use App\Repository\CalificacionesRepository;
use App\Repository\GrupoRepository;
use App\Repository\ReporteFinalRepository;
use App\Repository\SeguimientoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Form\FirmaType;
use App\Form\FotografiaType;
use App\Repository\EmpleadoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class JefeAcademicoController extends AbstractController
{
    #[Route('/home/jefe_academico', name: 'app_home_jefe_academico')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('jefe_academico/index.html.twig', [
            'controller_name' => 'JefeAcademicoController',
            'user' => $user,
        ]);
    }

    #[Route('/jefe_academico/mi_perfil', name: 'app_perfil_jefe_academico')]
    public function profile(ManagerRegistry $doctrine): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
   
        return $this->render('jefe_academico/perfil.html.twig', [
            'empleado' => $empleado,
        ]);
    }

    #[Route('/seguimiento/documentos', name: 'app_seguimiento_documentos')]
    public function verPlaneacionCurso(ManagerRegistry $doctrine, GrupoRepository $grupoRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $carreras = $empleado->getDepartamento()->getCarreras();

        $query = $grupoRepository->query_seguimiento_instrumentacion($carreras);
        $departamento = $empleado->getDepartamento();
       
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            8
        );
   
        return $this->render('jefe_academico/instrumentacion_plan_avance.html.twig', [
            'pagination' => $pagination,
            'empleado' => $empleado,
            'departamento' => $departamento,
        ]);
    }

    #[Route('/seguimiento/menu', name: 'app_seguimientos_menu')]
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
   
        return $this->render('jefe_academico/seguimiento_menu.html.twig', [

            'seg1' => $seguimiento1,
            'seg2' => $seguimiento2,
            'seg3' => $seguimiento3,
            'imagen'     => $imagenes,
           
        ]);
    }

    #[Route('/primer_seguimiento', name: 'app_seguimientos_1')]
    public function seguimientosPrimerPeriodo(ManagerRegistry $doctrine, GrupoRepository $grupoRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $carreras = $empleado->getDepartamento()->getCarreras();
        $departamento = $empleado->getDepartamento();

        $query = $grupoRepository->query_seguimiento_grupos($carreras);
       
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('jefe_academico/seguimiento_1.html.twig', [
            'empleado' => $empleado,
            'departamento' => $departamento,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/segundo_seguimiento', name: 'app_seguimientos_2')]
    public function seguimientosSegundoPeriodo(ManagerRegistry $doctrine, GrupoRepository $grupoRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $carreras = $empleado->getDepartamento()->getCarreras();
        $departamento = $empleado->getDepartamento();

        $query = $grupoRepository->query_seguimiento_grupos($carreras);
       
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('jefe_academico/seguimiento_2.html.twig', [
            'empleado' => $empleado,
            'pagination' => $pagination,
            'departamento' => $departamento,
        ]);
    }

    #[Route('/tercer_seguimiento', name: 'app_seguimientos_3')]
    public function seguimientosTercerPeriodo(ManagerRegistry $doctrine, GrupoRepository $grupoRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $carreras = $empleado->getDepartamento()->getCarreras();
        $departamento = $empleado->getDepartamento();

        $query = $grupoRepository->query_seguimiento_grupos($carreras);
        
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('jefe_academico/seguimiento_3.html.twig', [
            'empleado' => $empleado,
            'pagination' => $pagination,
            'departamento' => $departamento,
        ]);
    }


    #[Route('/primer_seguimiento/grupo/{id}', name: 'app_primer_seguimiento_grupo')]
    public function seguimientosPrimerTemas( Grupo $grupo, SeguimientoRepository $sr): Response
    {
        $usuario = $this->getUser();
        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoJFPar1($grupo_id);
        
        return $this->render('jefe_academico/grupo_primer_seg.html.twig', [
            'grupo' => $grupo,
            'seguimientos' => $seguimientos,
            'usuario' => $usuario,
        ]);
    }

    #[Route('/segundo_seguimiento/grupo/{id}', name: 'app_segundo_seguimiento_grupo')]
    public function seguimientosSegundoTemas( Grupo $grupo, SeguimientoRepository $sr): Response
    {
        $usuario = $this->getUser();
        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoJFPar2($grupo_id);
        
        return $this->render('jefe_academico/grupo_segundo_seg.html.twig', [
            'grupo' => $grupo,
            'seguimientos' => $seguimientos,
            'usuario' => $usuario,
        ]);
    }

    #[Route('/tercer_seguimiento/grupo/{id}', name: 'app_tercer_seguimiento_grupo')]
    public function seguimientosTercerTemas( Grupo $grupo, SeguimientoRepository $sr): Response
    {
        $usuario = $this->getUser();
        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoJFPar3($grupo_id);
        
        return $this->render('jefe_academico/grupo_tercer_seg.html.twig', [
            'grupo' => $grupo,
            'seguimientos' => $seguimientos,
            'usuario' => $usuario,
        ]);
    }

    #[Route('/primer_seguimiento/{id}/calificar', name: 'calificar_estado_seguimiento1', methods: ['GET', 'POST'])]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function registrarEstadoSeg1(Request $request,Seguimiento $seguimiento, SeguimientoRepository $seguimientoRepository): Response
    {

        if ($request->isMethod('POST')) {

            $estado = $_POST["estado"];
            $grupo  = $seguimiento->getGrupo()->getId();

            $seguimiento->setEstado($estado);
            $seguimientoRepository->save($seguimiento, true);

            return $this->redirectToRoute('app_primer_seguimiento_grupo', ['id'=>$grupo], Response::HTTP_SEE_OTHER);   
         
        }
        
    }
    
    #[Route('/segundo_seguimiento/{id}/calificar', name: 'calificar_estado_seguimiento2', methods: ['GET', 'POST'])]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function registrarEstadoSeg2(Request $request,Seguimiento $seguimiento, SeguimientoRepository $seguimientoRepository): Response
    {

        if ($request->isMethod('POST')) {

            $estado = $_POST["estado"];
            $grupo  = $seguimiento->getGrupo()->getId();

            $seguimiento->setEstado($estado);
            $seguimientoRepository->save($seguimiento, true);

            return $this->redirectToRoute('app_segundo_seguimiento_grupo', ['id'=>$grupo], Response::HTTP_SEE_OTHER);   
         
        }
        
    }

    #[Route('/tercer_seguimiento/{id}/calificar', name: 'calificar_estado_seguimiento3', methods: ['GET', 'POST'])]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function registrarEstadoSeg3(Request $request,Seguimiento $seguimiento, SeguimientoRepository $seguimientoRepository): Response
    {

        if ($request->isMethod('POST')) {

            $estado = $_POST["estado"];
            $grupo  = $seguimiento->getGrupo()->getId();

            $seguimiento->setEstado($estado);
            $seguimientoRepository->save($seguimiento, true);

            return $this->redirectToRoute('app_tercer_seguimiento_grupo', ['id'=>$grupo], Response::HTTP_SEE_OTHER);   
        }
        
    }

    #[Route('/reporte_final/{id}/calificar', name: 'calificar_reporte_final', methods: ['GET', 'POST'])]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function registrarEstadoRF(Request $request,ReporteFinal $reporteFinal, ReporteFinalRepository $seguimientoReporteFinal): Response
    {

        if ($request->isMethod('POST')) {

            $estado = $_POST["estado"];

            $reporteFinal->setEstado($estado);
            $seguimientoReporteFinal->save($reporteFinal, true);

            return $this->redirectToRoute('app_reporte_final_jefe_academico', [], Response::HTTP_SEE_OTHER);   
        }
        
    }

    #[Route('/primer_seguimiento/grupo/{id}/grafica', name: 'app_seg1_grupo_grafica')]
    public function graficarAproveachamiento1( ChartBuilderInterface $chartBuilder, Grupo $grupo, CalificacionesRepository $cr): Response
    {
        $usuario = $this->getUser();
        $resultados = $cr->BuscarCal1($grupo);
        $nombres = [];
        $calificiaciones = [];
        $clave = $grupo->getClaveGrupo();
        $materia = $grupo->getMateria()->getNombreMateria();

            if($resultados){

                foreach($resultados as $resultado){
             
                    $apellido = $resultado['paterno'];
                    $calificiacion = $resultado['calificacion'];

                    array_push($nombres,$apellido);
                    array_push($calificiaciones,$calificiacion);
                }

                $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

                $chart->setData([
                    'labels' => $nombres,
                    'datasets' => [
                        [

                            'label' => 'Calificación 1er Seguimiento',
                            'backgroundColor' => '#7393B3',
                            'borderColor' => 'rgb(255, 99, 132)',
                            'data' => $calificiaciones,
                        ],
                    ],
                ]);

                $chart->setOptions([
                    'scales' => [
                        'y' => [
                            'suggestedMin' => 0,
                            'suggestedMax' => 100,
                        ],
                    ],
                ]);

                return $this->render('jefe_academico/grupo_aprovech_seg1.html.twig', [
                    'clave' => $clave,
                    'materia' => $materia,
                    'chart' => $chart,
                    'usuario' => $usuario,
                ]);
            }
        
            return $this->redirectToRoute('app_seguimientos_1',[], Response::HTTP_SEE_OTHER); 
    }
    #[Route('/segundo_seguimiento/grupo/{id}/grafica', name: 'app_seg2_grupo_grafica')]
    public function graficarAproveachamiento2( ChartBuilderInterface $chartBuilder, Grupo $grupo, CalificacionesRepository $cr): Response
    {
        $usuario = $this->getUser();
        $resultados = $cr->BuscarCal2($grupo);
        $nombres = [];
        $calificiaciones = [];
        $clave = $grupo->getClaveGrupo();
        $materia = $grupo->getMateria()->getNombreMateria();

            if($resultados){

                foreach($resultados as $resultado){
             
                    $apellido = $resultado['paterno'];
                    $calificiacion = $resultado['calificacion'];

                    array_push($nombres,$apellido);
                    array_push($calificiaciones,$calificiacion);

                }

                $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

                $chart->setData([
                    'labels' => $nombres,
                    'datasets' => [
                        [

                            'label' => 'Calificación 2do Seguimiento',
                            'backgroundColor' => '#7393B3',
                            'borderColor' => 'rgb(255, 99, 132)',
                            'data' => $calificiaciones,
                        ],
                    ],
                ]);

                $chart->setOptions([
                    'scales' => [
                        'y' => [
                            'suggestedMin' => 0,
                            'suggestedMax' => 100,
                        ],
                    ],
                ]);

                return $this->render('jefe_academico/grupo_aprovech_seg2.html.twig', [
                    'clave' => $clave,
                    'materia' => $materia,
                    'grupo' => $grupo,
                    'chart' => $chart,
                    'usuario' => $usuario,
                ]);
            }
        
            return $this->redirectToRoute('app_seguimientos_2',[], Response::HTTP_SEE_OTHER); 
    }

    #[Route('/tercer_seguimiento/grupo/{id}/grafica', name: 'app_seg3_grupo_grafica')]
    public function graficarAproveachamiento3( ChartBuilderInterface $chartBuilder, Grupo $grupo, CalificacionesRepository $cr): Response
    {
        $usuario = $this->getUser();
        $resultados = $cr->BuscarCal3($grupo);
        $nombres = [];
        $calificiaciones = [];
        $clave = $grupo->getClaveGrupo();
        $materia = $grupo->getMateria()->getNombreMateria();

            if($resultados){

                foreach($resultados as $resultado){
             
                    $apellido = $resultado['paterno'];
                    $calificiacion = $resultado['calificacion'];

                    array_push($nombres,$apellido);
                    array_push($calificiaciones,$calificiacion);

                }

                $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

                $chart->setData([
                    'labels' => $nombres,
                    'datasets' => [
                        [

                            'label' => 'Calificación 3er Seguimiento',
                            'backgroundColor' => '#7393B3',
                            'borderColor' => 'rgb(255, 99, 132)',
                            'data' => $calificiaciones,
                        ],
                    ],
                ]);

                $chart->setOptions([
                    'scales' => [
                        'y' => [
                            'suggestedMin' => 0,
                            'suggestedMax' => 100,
                        ],
                    ],
                ]);

                return $this->render('jefe_academico/grupo_aprovech_seg3.html.twig', [
                    'clave' => $clave,
                    'materia' => $materia,
                    'grupo' => $grupo,
                    'chart' => $chart,
                    'usuario' => $usuario,
                ]);
            }
        
            return $this->redirectToRoute('app_seguimientos_3',[], Response::HTTP_SEE_OTHER); 
    }


    #[Route('/primer_seguimiento/grafica', name: 'app_seguimientos1_grafica')]
    public function chart1(ChartBuilderInterface $chartBuilder, ManagerRegistry $doctrine, SeguimientoRepository $seguimientoRepository): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $carreras = $empleado->getDepartamento()->getCarreras();

        $resultados = $seguimientoRepository->BuscarSeguimientosGRAFJA1($carreras);
        $graficas_1 = [];

            foreach($resultados as $resultado){

            $grupo = $resultado['clave_grupo'];
            $mat = $resultado['nombre_materia'];
            $titulo = $grupo.'-'.$mat;
            $porcentaje_aprobacion = $resultado['porc_aprob'];
            $porcentaje_no_aprobados = (100 - $porcentaje_aprobacion);

            $chart = $chartBuilder->createChart(Chart::TYPE_PIE);

            $chart->setData([
                'labels' => ['Aprobación', 'Reprobación'],
                'datasets' => [
                    [
                        'label' => 'Clase ',
                        'backgroundColor' => ['#236B8E','#C70039'],
                        'borderColor' => 'white',
                        'data' => [$porcentaje_aprobacion, $porcentaje_no_aprobados],
                    ],
                ],
            ]);

            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => 100,
                    ],
                ],
            ]);

            array_push($graficas_1,$chart);
        }

        return $this->render('jefe_academico/seguimiento_1_grafica.html.twig', [
            'chart' => $chart,
            'graficas_1' => $graficas_1,
            'user' => $user,
        ]);
    }

    
    #[Route('/segundo_seguimiento/grafica', name: 'app_seguimientos2_grafica')]
    public function chart2(ChartBuilderInterface $chartBuilder, ManagerRegistry $doctrine, SeguimientoRepository $seguimientoRepository): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $carreras = $empleado->getDepartamento()->getCarreras();

        $resultados = $seguimientoRepository->BuscarSeguimientosGRAFJA2($carreras);
        $graficas_1 = [];

            foreach($resultados as $resultado){
             
            $porcentaje_aprobacion = $resultado['porc_aprob'];
            $porcentaje_no_aprobados = (100 - $porcentaje_aprobacion);

            $chart = $chartBuilder->createChart(Chart::TYPE_PIE);

            $chart->setData([
                'labels' => ['Aprobación', 'Reprobación'],
                'datasets' => [
                    [
                        'label' => 'Clase ',
                        'backgroundColor' => ['#236B8E','#C70039'],
                        'borderColor' => 'white',
                        'data' => [$porcentaje_aprobacion, $porcentaje_no_aprobados],
                    ],
                ],
            ]);

            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => 100,
                    ],
                ],
            ]);

            array_push($graficas_1,$chart);
        }

        return $this->render('jefe_academico/seguimiento_2_grafica.html.twig', [
            'chart' => $chart,
            'graficas_1' => $graficas_1,
            'user' => $user,
        ]);
    }

    
    #[Route('/tercer_seguimiento/grafica', name: 'app_seguimientos3_grafica')]
    public function chart3(ChartBuilderInterface $chartBuilder, ManagerRegistry $doctrine, SeguimientoRepository $seguimientoRepository): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $carreras = $empleado->getDepartamento()->getCarreras();

        $resultados = $seguimientoRepository->BuscarSeguimientosGRAFJA3($carreras);
        $graficas_1 = [];

            foreach($resultados as $resultado){
             
            $porcentaje_aprobacion = $resultado['porc_aprob'];
            $porcentaje_no_aprobados = (100 - $porcentaje_aprobacion);

            $chart = $chartBuilder->createChart(Chart::TYPE_PIE);

            $chart->setData([
                'labels' => ['Aprobación', 'Reprobación'],
                'datasets' => [
                    [
                        'label' => 'Clase ',
                        'backgroundColor' => ['#236B8E','#C70039'],
                        'borderColor' => 'white',
                        'data' => [$porcentaje_aprobacion, $porcentaje_no_aprobados],
                    ],
                ],
            ]);

            $chart->setOptions([
                'scales' => [
                    'y' => [
                        'suggestedMin' => 0,
                        'suggestedMax' => 100,
                    ],
                ],
            ]);

            array_push($graficas_1,$chart);
        }

        return $this->render('jefe_academico/seguimiento_3_grafica.html.twig', [
            'chart' => $chart,
            'graficas_1' => $graficas_1,
            'user' => $user,
        ]);
    }

    #[Route('/reporte_final', name: 'app_reporte_final_jefe_academico')]
    public function reporteFinal(ManagerRegistry $doctrine, ReporteFinalRepository $reporteFinalRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $user = $this->getUser();
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]); 
        $departamento = $empleado->getDepartamento();

        $year = date("Y");
        $actMonth = date('m');
        $periodo = 0;
    
        if($actMonth >=1 && $actMonth <= 9){

            $periodo = 1; 

        } elseif($actMonth >=  10 && $actMonth <= 12){
           
            $periodo = 2; 
        }
        
        $semestre = $year.'-'.$periodo;

        $query = $reporteFinalRepository->query_reporteFinal($semestre,$departamento);

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('jefe_academico/seguimiento_reporte_final.html.twig', [
            'departamento' => $departamento,
            'semestre' => $semestre,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/jefe_academico/cambiar_password', name: 'cambiar_password_jefeacademico')]
    public function cambiarPassword(): Response
    {

        $user = $this->getUser();
        return $this->render('usuario/cambiar_password_jefe_ac.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/jefe_academico/{id}/fotografias', name: 'app_jefe_cambiar_fotografia',methods: ['GET', 'POST'])]
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
        $newFilename = null;

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

                return $this->redirectToRoute('app_perfil_jefe_academico', [], Response::HTTP_SEE_OTHER);
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

                return $this->redirectToRoute('app_perfil_jefe_academico', [], Response::HTTP_SEE_OTHER);
            }
            
        }

        return $this->renderForm('jefe_academico/mi_imagen.html.twig',[
            'form1'  => $form1,
            'form2'  => $form2,
            'user' => $user,
            'firma' => $firma,
            'fotografia' => $fotografia,
        ]);
         

    }


}
