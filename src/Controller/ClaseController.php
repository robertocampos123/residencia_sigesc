<?php

namespace App\Controller;

use App\Entity\Calificaciones;
use App\Entity\Empleado;
use App\Entity\Grupo;
use App\Entity\Periodo;
use App\Entity\ReporteGrupo;
use App\Repository\GrupoRepository;
use App\Entity\Seguimiento;
use App\Entity\Tema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\InstrumentacionType;
use App\Repository\CalificacionesRepository;
use App\Repository\ReporteGrupoRepository;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\SeguimientoRepository;
use App\Repository\TemaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Filesystem\Filesystem;


#[Route('/clase')]
class ClaseController extends AbstractController
{
    

    #[Route('/{id}', name: 'app_clase', methods: ['GET'])]
    public function show(Grupo $grupo): Response
    {
        $user = $this->getUser();
        $instrumentacion = $grupo->getInstrumentacionDidactica();

        return $this->render('clase/index.html.twig', [
            'grupo' => $grupo,
            'user' => $user,
            'instrumentacion' => $instrumentacion,
        ]);
    }

    #[Route('/{id}/alumnos', name: 'app_clase_alumnos', methods: ['GET'])]
    public function mostrarLista(Grupo $grupo, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $repository = $doctrine->getRepository(Calificaciones::class);
        $calificaciones = $repository->findBy(['grupo'=> $grupo],['alumno'=>'ASC']);

        return $this->render('clase/lista_alumnos.html.twig', [
            'calificaciones' => $calificaciones,
            'user' => $user,
            'grupo' => $grupo

        ]);
    }


    #[Route('/{id}/subir_instrumentacion_didactica', name: 'app_clase_subir_instrumentacion_didactica',methods: ['GET', 'POST'])]
    public function import(Grupo $grupo, HttpFoundationRequest $request, GrupoRepository $grupoRepository, SluggerInterface $slugger): Response

    {
        $user = $this->getUser();
        $grupo_id = $grupo->getId();
        $instrumentacion = $grupo->getInstrumentacionDidactica();
        $form = $this->createForm(InstrumentacionType::class, $grupo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $archivo = $form->get('instrumentacion_didactica')->getData();

            if ($archivo) {

                if($instrumentacion){

                    $filesystem = new Filesystem();
                    $path=$this->getParameter("instrumentacion_directory").'/'.$instrumentacion;
                    $filesystem->remove($path);
    
                }


                $originalFilename = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$archivo->guessExtension();
                try {
                    $archivo->move(
                        $this->getParameter('instrumentacion_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

              
                
                $grupo->setInstrumentacionDidactica($newFilename);
                $grupoRepository->save($grupo, true);

                //{{ path('app_carrera_delete', {'id': carrera.id}) }}

                return $this->redirectToRoute('app_clase', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);
            }
            
        }
     
        return $this->renderForm('clase/subir_instrumentacion_didactica.html.twig',[
            'grupo' => $grupo,
            'instrumentacion' => $instrumentacion,
            'form'  => $form,
            'user' => $user,
        ]);
         

    }


    #[Route('/{id}/plan/avance', name: 'app_plan_avance')]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function generarSeguimiento(Request $request, Grupo $grupo, ManagerRegistry $doctrine): Response
    {

        $user = $this->getUser();
        $grupo_id = $grupo->getId();
        $materia = $grupo->getMateria();
        $repository = $doctrine->getRepository(Tema::class);
        $repository_parcial = $doctrine->getRepository(Periodo::class);
        $repository_seguimiento = $doctrine->getRepository(Seguimiento::class);
        $temas = $repository->findBy(['materia'=>$materia],['numero_unidad'=>'ASC']);
        $existen = $repository_seguimiento->findBy(['grupo'=>$grupo_id]);
        //$no_tema = $temas->count(['materia' => $materia]);
        
        $parcial1 = new Periodo();
        $parcial2 = new Periodo();
        $parcial3 = new Periodo();

        $parcial1 = $repository_parcial->findOneBy(['clave_periodo' => 'PARC1']);
        $parcial2 = $repository_parcial->findOneBy(['clave_periodo' => 'PARC2']);
        $parcial3 = $repository_parcial->findOneBy(['clave_periodo' => 'PARC3']);

        $parcial1_cierre = ($parcial1->getFin());
        $parcial2_cierre = ($parcial2->getFin());
        $parcial3_cierre = ($parcial3->getFin());


        if ($request->isMethod('POST')) {
            

            $i=1;
            foreach($temas as $tema)
            {

            $em = $doctrine->getManager();

            $programado_inicio = $_POST["programado_inicio$i"];
            $programado_cierre = $_POST["programado_cierre$i"];
            $programado_evaluacion = $_POST["programado_eval$i"];

            $inicio = new \DateTime($programado_inicio);
            $cierre = new \DateTime($programado_cierre);
            $ev     = new \DateTime($programado_evaluacion);


            $seguimiento = new Seguimiento();
            $seguimiento->setFechaProgInicio($inicio);
            $seguimiento->setFechaProgFin($cierre);
            $seguimiento->setEvaluacionProgramada($ev);
            $seguimiento->setGrupo($grupo);
            $seguimiento->setTema($tema);


           if($cierre < $parcial1_cierre){

            $seguimiento->setParcial($parcial1);

            }else if($cierre > $parcial1_cierre && $cierre < $parcial2_cierre ){

                $seguimiento->setParcial($parcial2);

                }else if($cierre > $parcial2_cierre && $cierre < $parcial3_cierre ){
                
                    $seguimiento->setParcial($parcial3);
                    }
            
            $em->persist($seguimiento);
            $em->flush();  

            $i++;

            }
            return $this->redirectToRoute('app_clase', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);
        
        }

        return $this->render('clase/plan_avance.html.twig', [

            'grupo' => $grupo,
            'temas' => $temas,
            'user' => $user,
            'existen' => $existen,
        ]);
    }

    #[Route('/{id}/primer_avance', name: 'app_primer_avance')]
    
    public function capturarParcial1(Request $request, SeguimientoRepository $sr ,Grupo $grupo, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoPar1($grupo_id);
        
        return $this->render('clase/avance_primer.html.twig', [
            'grupo' => $grupo,
            'seguimientos' => $seguimientos,
            'user' => $user,
            
        ]);
    }

    #[Route('/{id}/segundo_avance', name: 'app_segundo_avance')]
    
    public function capturarParcial2(Request $request, SeguimientoRepository $sr ,Grupo $grupo, ManagerRegistry $doctrine): Response
    {
        
        $user = $this->getUser();
        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoPar2($grupo_id);
        
        return $this->render('clase/avance_segundo.html.twig', [
            'grupo' => $grupo,
            'seguimientos' => $seguimientos,
            'user' => $user,
        ]);
    }

    #[Route('/{id}/tercer_avance', name: 'app_tercer_avance')]
    
    public function capturarParcial3(Request $request, SeguimientoRepository $sr ,Grupo $grupo, ManagerRegistry $doctrine): Response
    {
        
        $user = $this->getUser();
        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoPar3($grupo_id);
        
        return $this->render('clase/avance_tercer.html.twig', [
            'grupo' => $grupo,
            'seguimientos' => $seguimientos,
            'user' => $user,
        ]);
    }



    #[Route('/registrar_seguimiento/{id}', name: 'app_registrar_avance', methods: ['GET', 'POST'])]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function registrarAvance(Request $request,Seguimiento $seguimiento, SeguimientoRepository $seguimientoRepository, ManagerRegistry $doctrine): Response
    {

        if ($request->isMethod('POST')) {

            $real_inicio     = $_POST["real_inicio"];
            $real_cierre     = $_POST["real_cierre"];
            $real_evaluacion = $_POST["real_evaluacion"];
            $observaciones       = $_POST["observaciones"];
            $evidencia   = $_POST["evidencia"];

            $inicio = new \DateTime($real_inicio);
            $cierre = new \DateTime($real_cierre);
            $ev     = new \DateTime($real_evaluacion);

            $grupo = $seguimiento->getGrupo()->getId();
            
            $seguimiento->setFechaRealInicio($inicio);
            $seguimiento->setFechaRealFin($cierre);
            $seguimiento->setEvaluacionReal($ev);
            $seguimiento->setEvidencia($evidencia);
            $seguimiento->setObservaciones($observaciones);
            $seguimientoRepository->save($seguimiento, true);
        
            return $this->redirectToRoute('app_clase', ['id'=>$grupo], Response::HTTP_SEE_OTHER);    
        }

        return $this->render('clase/evaluar_avance.html.twig', [

            'seguimiento' => $seguimiento,
            
        ]);
   }


        #[Route('/{id}/archivo/plan_avance', name: 'app_archivo_plan_avance')]

        public function index(ManagerRegistry $doctrine, Grupo $grupo, SeguimientoRepository $sr, TemaRepository $temaRepository )
        {
        
        
        $grupo_id = $grupo->getId();
        $grupo_materia = $grupo->getMateria();
        $depto = $grupo->getCarrera()->getDepartamento();
        $seguimientos = $sr->BuscarSeguimientos($grupo_id);
        $numero_temas = $temaRepository->numeroTemas($grupo_materia);

        $repository3 = $doctrine->getRepository(Periodo::class);
        $repository4 = $doctrine->getRepository(Empleado::class);
        $semestre    = $repository3->findOneBy(['clave_periodo'=>'SEMACT']);
        $parc1       = $repository3->findOneBy(['clave_periodo'=>'PARC1']);
        $parc2       = $repository3->findOneBy(['clave_periodo'=>'PARC2']);
        $parc3       = $repository3->findOneBy(['clave_periodo'=>'PARC3']);
        $entreg      = $repository3->findOneBy(['clave_periodo'=>'REPFIN']);
        $jefeDepto   = $repository4->findOneBy(['departamento'=>$depto, 'cargo'=>'3']);


        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled',true);
        
        
        // Crea una instancia de Dompdf con nuestras opciones
        $dompdf = new Dompdf($pdfOptions);
        //$dompdf = new Dompdf(["chroot" => ["/public/uploads/firma"]]);
        
        // Recupere el HTML generado en nuestro archivo twig
        $html = $this->renderView('clase/prueba.html.twig', [
            'title' => "PDF",
            'grupo' => $grupo,
            'temas' => $numero_temas,
            'seguimientos' => $seguimientos,
            'semestre' => $semestre,
            'parc1'    => $parc1,
            'parc2'    => $parc2,
            'parc3'    => $parc3,
            'entrega'    => $entreg,
            'jefe_depto' =>$jefeDepto,
        ]);
        
        // Cargar HTML en Dompdf
        $dompdf->loadHtml($html);
        
        // (Opcional) Configure el tamaño del papel y la orientación 'vertical' o 'vertical'
        $dompdf->setPaper('A4', 'landscape');

        // Renderiza el HTML como PDF
        $dompdf->render();
        
        ob_get_clean();

        // Envíe el PDF generado al navegador (vista en línea)
        $dompdf->stream("planeación_del_curso.pdf", [
            "Attachment" => false,
        ]);

    
    }


    #[Route('/{id}/primer_avance/calificar', name: 'app_calificar_primer_avance')]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function calificar_primer_periodo(Request $request, Grupo $grupo, SeguimientoRepository $sr ,CalificacionesRepository $calificacionesRepository, GrupoRepository $grupoRepository,ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(Calificaciones::class);
        $calificaciones = $repository->findBy(['grupo'=>$grupo], ['alumno'=>'ASC']);

        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoPar1($grupo_id);

        $no_alu=  $grupoRepository->noAlumnos($grupo);
        
        if ($request->isMethod('POST')) {

            $i=1;
            $resultados_aprobacion = 0;
            
            foreach($calificaciones as $calificacion)

            {
                $calificacion_alumno = $_POST["calificacion$i"];
                $cf = (float) $calificacion_alumno;
                $calificacion->setPeriodoUno($cf);
                $calificacionesRepository->save($calificacion, true);


                if($calificacion_alumno >= 70 && $calificacion_alumno <= 100 ){
                    $resultados_aprobacion = $resultados_aprobacion + 1;
                }

                $i++;
            }

            $porcentaje_aprobacion = (($resultados_aprobacion * 100)/($no_alu));
            $aprobacion = round($porcentaje_aprobacion, 2); 

            if(empty($seguimientos)){

                
                return $this->redirectToRoute('app_clase_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);     
            

            }elseif(!empty($seguimientos)){

                foreach($seguimientos as $seguimiento)

                    {
                        $seguimiento->setPorcentajeAprobacion($aprobacion);
                        $sr->save($seguimiento, true);

                    }

                return $this->redirectToRoute('app_clase_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);    
                  
            }
    
        }

        return $this->render('clase/calificar_primer.html.twig', [

            'calificaciones' => $calificaciones,
            'grupo' =>$grupo,
            
        ]);
    }

    #[Route('/{id}/segundo_avance/calificar', name: 'app_calificar_segundo_avance')]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function calificar_segundo_periodo(Request $request, Grupo $grupo, SeguimientoRepository $sr ,CalificacionesRepository $calificacionesRepository, GrupoRepository $grupoRepository, ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(Calificaciones::class);
        $calificaciones    = $repository->findBy(['grupo'=>$grupo], ['alumno'=>'ASC']);

        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoPar2($grupo_id);
        $no_alu=  $grupoRepository->noAlumnos($grupo);
        
        if ($request->isMethod('POST')) {

            $i=1;
            $resultados_aprobacion = 0;
            
            foreach($calificaciones as $calificacion)

            {
                $calificacion_alumno = $_POST["calificacion$i"];
                $cf = (float) $calificacion_alumno;
                $calificacion->setPeriodoDos($cf);
                $calificacionesRepository->save($calificacion, true);

                if($calificacion_alumno >= 70 && $calificacion_alumno <= 100 ){
                    $resultados_aprobacion = $resultados_aprobacion + 1;
                }

                $i++;
            }

            $porcentaje_aprobacion = (($resultados_aprobacion * 100)/($no_alu));
            $aprobacion = round($porcentaje_aprobacion, 2); 

            if(empty($seguimientos)){

                return $this->redirectToRoute('app_clase_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);  

                }elseif(!empty($seguimientos)){

                    foreach($seguimientos as $seguimiento)

                        {
                            $seguimiento->setPorcentajeAprobacion($aprobacion);
                            $sr->save($seguimiento, true);
                        }

                    return $this->redirectToRoute('app_clase_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);   
                }
        }

        return $this->render('clase/calificar_segundo.html.twig', [

            'calificaciones' => $calificaciones,
            'grupo' =>$grupo,
            
        ]);
    }

    #[Route('/{id}/tercer_avance/calificar', name: 'app_calificar_tercer_avance')]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function calificar_tercer_periodo(Request $request, Grupo $grupo, SeguimientoRepository $sr ,CalificacionesRepository $calificacionesRepository, GrupoRepository $grupoRepository, ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(Calificaciones::class);
        $calificaciones    = $repository->findBy(['grupo'=>$grupo], ['alumno'=>'ASC']);

        $grupo_id = $grupo->getId();
        $seguimientos = $sr->BuscarSeguimientoPar3($grupo_id);
        $no_alu=  $grupoRepository->noAlumnos($grupo);
        
        if ($request->isMethod('POST')) {

            $i=1;
            $resultados_aprobacion = 0;
            
            foreach($calificaciones as $calificacion)

            {
                $calificacion_alumno = $_POST["calificacion$i"];
                $cf = (float) $calificacion_alumno;
                $calificacion->setPeriodoTres($cf);
                $calificacionesRepository->save($calificacion, true);

                if($calificacion_alumno >= 70 && $calificacion_alumno <= 100 ){
                    $resultados_aprobacion = $resultados_aprobacion + 1;
                }

                $i++;
            }
            
            $porcentaje_aprobacion = (($resultados_aprobacion * 100)/($no_alu));
            $aprobacion = round($porcentaje_aprobacion, 2); 

            if(empty($seguimientos)){

                return $this->redirectToRoute('app_clase_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);    
            

            }elseif(!empty($seguimientos)){

                foreach($seguimientos as $seguimiento)

                {
                    $seguimiento->setPorcentajeAprobacion($aprobacion);
                    $sr->save($seguimiento, true);

                }

                return $this->redirectToRoute('app_clase_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);    
                  
            }
            
        }

        return $this->render('clase/calificar_tercero.html.twig', [

            'calificaciones' => $calificaciones,
            'grupo' => $grupo,
            
        ]);
    }

    #[Route('/{id}/final/calificar', name: 'app_calificar_final')]
    //#[ParamConverter('id', class:Grupo::class, options:['id'-> 'id'])]
    public function calificar_final(Request $request, Grupo $grupo, ReporteGrupoRepository $reporteGrupoRepository, ManagerRegistry $doctrine): Response
    {

        $user = $this->getUser();
        $grupo_id = $grupo->getId();
        $alumnos = $grupo->getInscritos();
        $repository = $doctrine->getRepository(ReporteGrupo::class);
        $reporte_grupo = $repository->findOneBy(['grupo'=>$grupo]);
        $suma_reporte = 0;

        if ($request->isMethod('POST')) {


            $acreditados_ordinario  = $_POST["a"];
            $acreditados_regularizacion = $_POST["b"];
            $acreditados_extraordinario = $_POST["c"];
            $no_acreditados = $_POST["d"];
            $desertados = $_POST["e"];

            $suma_reporte = $acreditados_ordinario + $acreditados_regularizacion 
                          + $acreditados_extraordinario + $no_acreditados;

            if($suma_reporte == $alumnos){

                $reporte_grupo->setAcOrdinario($acreditados_ordinario);
                $reporte_grupo->setAcRegularizacion($acreditados_regularizacion);
                $reporte_grupo->setAcExtraordinario($acreditados_extraordinario);
                $reporte_grupo->setNoAcreditado($no_acreditados);
                $reporte_grupo->setDesertados($desertados);
            

                $reporteGrupoRepository->save($reporte_grupo, true);

                return $this->redirectToRoute('app_clase', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);

                    }elseif(!$suma_reporte == $alumnos){

                    return $this->redirectToRoute('app_clase', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);

                    }

            }  
            
            return $this->render('clase/reportar_final.html.twig', [

                'reporte' => $reporte_grupo,
                'grupo' =>$grupo,
                'user' => $user,
            ]);
    }
}
