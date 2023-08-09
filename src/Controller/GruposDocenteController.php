<?php

namespace App\Controller;

use App\Entity\Empleado;
use App\Entity\Grupo;
use App\Entity\Periodo;
use App\Entity\ReporteFinal;
use App\Repository\CalificacionesRepository;
use App\Repository\GrupoRepository;
use App\Repository\ReporteFinalRepository;
use App\Repository\ReporteGrupoRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class GruposDocenteController extends AbstractController
{
    #[Route('/grupos/docente', name: 'app_grupos_docente')]
    public function index(ManagerRegistry $doctrine, GrupoRepository $grupoRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $user = $this->getUser();
        
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        $query = $grupoRepository->queryMenu($empleado);
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            2
        );

        $imagenes = [
            "https://cdn-icons-png.flaticon.com/512/3301/3301569.png", 
            "https://cdn-icons-png.flaticon.com/512/3301/3301775.png", 
            "https://cdn-icons-png.flaticon.com/512/3301/3301770.png",
            "https://cdn-icons-png.flaticon.com/512/3301/3301747.png",
            "https://cdn-icons-png.flaticon.com/512/3301/3301740.png",
            "https://cdn-icons-png.flaticon.com/512/3301/3301710.png",
        ];

        return $this->render('grupos_docente/index.html.twig', [
            'pagination' => $pagination,
            'imagen' =>$imagenes
        ]);
    }


    #[Route('/grupos/reporte_final', name: 'app_grupos_reporte_final')]
    public function reporteFinal(ManagerRegistry $doctrine,  ReporteGrupoRepository $reporteGrupoRepository): Response
    {

        $user = $this->getUser();
        
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        
    
        $grupos = $reporteGrupoRepository->reporteGrup($empleado);

        return $this->render('grupos_docente/generar_reporte_final.html.twig', [
            'grupos' => $grupos
        ]);
    }

    #[Route('/grupos/reporte_final/archivo', name: 'app_archivo_reporte_final')]

    public function generarReporteFinal( ManagerRegistry $doctrine, GrupoRepository $grupoRepository, ReporteGrupoRepository $reporteGrupoRepository)
    {
    
    $user = $this->getUser();

        
    $repository1 = $doctrine->getRepository(Empleado::class);
    $empleado = $repository1->findOneBy(['mail'=>$user]);

    $depto = $empleado->getDepartamento();

    $repository3 = $doctrine->getRepository(Periodo::class);
    $semestre    = $repository3->findOneBy(['clave_periodo'=>'SEMACT']);

    $repository4 = $doctrine->getRepository(Empleado::class);
    $jefeDepto   = $repository4->findOneBy(['departamento'=>$depto, 'cargo'=>'3']);
    

    $num_grupos = $grupoRepository->numeroGrupos($empleado);
    $materias_diferentes = $grupoRepository->materiasImpartidas($empleado);
    $grupos = $reporteGrupoRepository->reporteGrup($empleado);

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->set('isRemoteEnabled',true);
    
    // Crea una instancia de Dompdf con nuestras opciones
    $dompdf = new Dompdf($pdfOptions);
    
    // Recupere el HTML generado en nuestro archivo twig
    $html = $this->renderView('grupos_docente/reporte_final.html.twig', [
        'empleado' => $empleado,
        'no_grupos' => $num_grupos,
        'materias_diferentes' => $materias_diferentes,
        'grupos' => $grupos,
        'semestre' => $semestre,
        'jefe_depto' => $jefeDepto,
    ]);
    
    // Cargar HTML en Dompdf
    $dompdf->loadHtml($html);
    
    // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
    $dompdf->setPaper('A4', 'vertical');

    // Renderiza el HTML como PDF
    $dompdf->render();
    
    ob_get_clean();

    // Envíe el PDF generado al navegador (vista en línea)
    $dompdf->stream("reporte_final.pdf", [
        "Attachment" => false
    ]);

    }

#[Route('/reporte_final/guardar', name: 'app_guardar_reporte_final')]

    public function guardarReporteFinal( ManagerRegistry $doctrine, GrupoRepository $grupoRepository, ReporteGrupoRepository $reporteGrupoRepository, ReporteFinalRepository $reporteFinalRepository, SluggerInterface $slugger)
    {
    
    $user = $this->getUser();
        
    $repository1 = $doctrine->getRepository(Empleado::class);
    $empleado    = $repository1->findOneBy(['mail'=>$user]);
    $repository3 = $doctrine->getRepository(Periodo::class);
    $semestre    = $repository3->findOneBy(['clave_periodo'=>'SEMACT']);
    $rfc_emp     = $empleado->getRFC();
    $year = date("Y");
    $actMonth = date('m');
    $periodo = 0;

    if($actMonth >=1 && $actMonth <= 7){
        $periodo = 1; 
    } elseif($actMonth >= 9 && $actMonth <= 12){
        $periodo = 2; 
    }
    
    $periodo_act = $year.'-'.$periodo;

    $num_grupos = $grupoRepository->numeroGrupos($empleado);
    $materias_diferentes = $grupoRepository->materiasImpartidas($empleado);
    $grupos = $reporteGrupoRepository->sumaEv($empleado);

    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->set('isRemoteEnabled',true);
    
    // Crea una instancia de Dompdf con nuestras opciones
    $dompdf = new Dompdf($pdfOptions);
    
    // Recupere el HTML generado en nuestro archivo twig
    $html = $this->renderView('grupos_docente/reporte_final.html.twig', [
        'empleado' => $empleado,
        'no_grupos' => $num_grupos,
        'materias_diferentes' => $materias_diferentes,
        'grupos' => $grupos,
        'semestre' => $semestre,
    ]);
    
    // Cargar HTML en Dompdf
    $dompdf->loadHtml($html);
    
    // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
    $dompdf->setPaper('A4', 'vertical');

    // Renderiza el HTML como PDF
    $dompdf->render();
    //ob_get_clean();

     // Almacenar datos binarios PDF
    $output = $dompdf->output();
        
    // En este caso, queremos escribir el archivo en el directorio público.
    $publicDirectory = $this->getParameter('reporte_final_directory');
     // e.g /var/www/project/public/mypdf.pdf
    $nombreArchivo = 'ReporteFinal'.$rfc_emp.'-'.$periodo_act.'.'.'pdf';
    $pdfFilepath =  $publicDirectory . '/'.$nombreArchivo;

    $archivo_existente    = $reporteFinalRepository->findOneBy(['reporte_final'=>$nombreArchivo]);

    if($archivo_existente){

        $filesystem = new Filesystem();
        $path=$this->getParameter("reporte_final_directory").'/'.$nombreArchivo;
        $filesystem->remove($path);

        file_put_contents($pdfFilepath, $output);
        $archivo_existente->setEstado("En Revisión");
        $archivo_existente->setReporteFinal($nombreArchivo);
        $reporteFinalRepository->save($archivo_existente, true);
        return $this->redirectToRoute('app_grupos_reporte_final',[], Response::HTTP_SEE_OTHER); 

      }  else if(!$archivo_existente){

            file_put_contents($pdfFilepath, $output);

            $em = $doctrine->getManager();
            $reporte_final = new ReporteFinal;

            $reporte_final->setReporteFinal($nombreArchivo);
            $reporte_final->setEmpleado($empleado);
            $reporte_final->setPeriodo($periodo_act);
            $reporte_final->setEstado("En Revisión");
            $em->persist($reporte_final);
            $em->flush();

            return $this->redirectToRoute('app_grupos_reporte_final',[], Response::HTTP_SEE_OTHER); 

        }

}

#[Route('/estadisticas/primer_seguimiento', name: 'app_docente_estadisticas_seg1')]
    public function docenteSeguimiento1(ManagerRegistry $doctrine,  ReporteGrupoRepository $reporteGrupoRepository): Response
    {

        $user = $this->getUser();
        
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        $grupos = $reporteGrupoRepository->reporteGrup($empleado);

        return $this->render('grupos_docente/lista_seguimientos_1.html.twig', [
            'grupos' => $grupos
        ]);
    }

    #[Route('/estadisticas/segundo_seguimiento', name: 'app_docente_estadisticas_seg2')]
    public function docenteSeguimiento2(ManagerRegistry $doctrine,  ReporteGrupoRepository $reporteGrupoRepository): Response
    {

        $user = $this->getUser();
        
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        $grupos = $reporteGrupoRepository->reporteGrup($empleado);

        return $this->render('grupos_docente/lista_seguimientos_2.html.twig', [
            'grupos' => $grupos
        ]);
    }

    #[Route('/estadisticas/tercer_seguimiento', name: 'app_docente_estadisticas_seg3')]
    public function docenteSeguimiento3(ManagerRegistry $doctrine,  ReporteGrupoRepository $reporteGrupoRepository): Response
    {

        $user = $this->getUser();
        
        $repository1 = $doctrine->getRepository(Empleado::class);
        $empleado = $repository1->findOneBy(['mail'=>$user]);
        $grupos = $reporteGrupoRepository->reporteGrup($empleado);

        return $this->render('grupos_docente/lista_seguimientos_3.html.twig', [
            'grupos' => $grupos
        ]);
    }

    #[Route('/estadisticas/primer_seguimiento/grupo/{id}/grafica', name: 'app_docente_estadisticas_seg1_grupo')]
    public function graficarAproveachamiento1( ChartBuilderInterface $chartBuilder, Grupo $grupo, CalificacionesRepository $cr): Response
    {
        $usuario = $this->getUser();
        $resultados = $cr->BuscarCal1($grupo);
        $nombres = [];
        $calificiaciones = [];

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
                            'backgroundColor' => '#5F9DF7',
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

                return $this->render('grupos_docente/estadistica_grupo_seg1.html.twig', [
                    'grupo' => $grupo,
                    'chart' => $chart,
                    'usuario' => $usuario,
                ]);
            }
        
            return $this->redirectToRoute('app_docente_estadisticas_seg1',[], Response::HTTP_SEE_OTHER); 
    }

    #[Route('/estadisticas/segundo_seguimiento/grupo/{id}/grafica', name: 'app_docente_estadisticas_seg2_grupo')]
    public function graficarAproveachamiento2( ChartBuilderInterface $chartBuilder, Grupo $grupo, CalificacionesRepository $cr): Response
    {
        $usuario = $this->getUser();
        $resultados = $cr->BuscarCal2($grupo);
        $nombres = [];
        $calificiaciones = [];

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
                            'backgroundColor' => '#5F9DF7',
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

                return $this->render('grupos_docente/estadistica_grupo_seg2.html.twig', [
                    'grupo' => $grupo,
                    'chart' => $chart,
                    'usuario' => $usuario,
                ]);
            }
        
            return $this->redirectToRoute('app_docente_estadisticas_seg2',[], Response::HTTP_SEE_OTHER); 
    }

    #[Route('/estadisticas/tercer_seguimiento/grupo/{id}/grafica', name: 'app_docente_estadisticas_seg3_grupo')]
    public function graficarAproveachamiento3( ChartBuilderInterface $chartBuilder, Grupo $grupo, CalificacionesRepository $cr): Response
    {
        $usuario = $this->getUser();
        $resultados = $cr->BuscarCal3($grupo);
        $nombres = [];
        $calificiaciones = [];

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
                            'backgroundColor' => '#5F9DF7',
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

                return $this->render('grupos_docente/estadistica_grupo_seg3.html.twig', [
                    'grupo' => $grupo,
                    'chart' => $chart,
                    'usuario' => $usuario,
                ]);
            }
        
            return $this->redirectToRoute('app_docente_estadisticas_seg3',[], Response::HTTP_SEE_OTHER); 
    }

   

}
