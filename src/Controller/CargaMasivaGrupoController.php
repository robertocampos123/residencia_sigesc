<?php

namespace App\Controller;

use App\Entity\Carrera;
use App\Entity\Empleado;
use App\Entity\Materia;
use App\Entity\Grupo;
use App\Entity\ReporteGrupo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Doctrine\Persistence\ManagerRegistry;

class CargaMasivaGrupoController extends AbstractController
{
    #[Route('/carga/masiva/grupo', name: 'app_carga_masiva_grupo')]
    public function index(): Response
    {
        return $this->render('carga_masiva_grupo/index.html.twig', [
            'controller_name' => 'CargaMasivaGrupoController',
        ]);
    }


        
    #[Route('/carga/grupo/archivoimport', name: 'app_carga_masiva_grupo_archivoimport')]
    public function import(Request $request, ManagerRegistry $doctrine)
    {
        if ($request->isMethod('POST')) {

        $upload_file=$_FILES['file_grupo']['name'];
        $extension=pathinfo($upload_file, PATHINFO_EXTENSION);

            if('csv' == $extension){
                $reader = new ReaderCsv();
            }else if('xlsx' == $extension){
                $reader = new ReaderXlsx();
            }else{
                $reader = new ReaderXlsx();
            }
            
            $spreadsheet = $reader->load($_FILES['file_grupo']['tmp_name']);
            $sheetdata=$spreadsheet->getActiveSheet()->toArray();
            $sheetcount=count($sheetdata);

            if($sheetcount>1){
                //$data=array();
                for($i=1; $i < $sheetcount; $i++) {
                    
                    
                    $grupo = new Grupo ();
                    $reporte_grupo = new ReporteGrupo();
                    $em = $doctrine->getManager();

                    $repositoryMateria = $doctrine->getRepository(Materia::class);
                    $repositoryEmpleado = $doctrine->getRepository(Empleado::class);
                    $repositoryCarrera = $doctrine->getRepository(Carrera::class);

                    $clave_grupo=$sheetdata[$i][1];
                    $clave_materia =$sheetdata[$i][2];
                    $cupo =$sheetdata[$i][3];
                    $inscritos =$sheetdata[$i][4];
                    $rfc_docente =$sheetdata[$i][5];
                    $aula =$sheetdata[$i][6];
                    $horario =$sheetdata[$i][7];
                    $carrera =$sheetdata[$i][8];

                    $obj_materia = $repositoryMateria->findOneBy(['clave_materia' => $clave_materia]);
                    $obj_docente = $repositoryEmpleado->findOneBy(['RFC' => $rfc_docente]);
                    $obj_carrera = $repositoryCarrera->findOneBy(['clave_carrera' => $carrera]);
                   
                    $grupo->setClaveGrupo($clave_grupo);
                    $grupo->setMateria($obj_materia);
                    $grupo->setCupo($cupo);
                    $grupo->setInscritos($inscritos);
                    $grupo->setDocente($obj_docente);
                    $grupo->setHorario($horario);
                    $grupo->setAula($aula);
                    $grupo->setCarrera($obj_carrera);

                    $em->persist($grupo);
                    $em->flush();  

                    $reporte_grupo->setGrupo($grupo);
                    $em->persist($reporte_grupo);
                    $em->flush();  
                }
            }

            return $this->redirectToRoute('app_grupo_index');

        }}
}
