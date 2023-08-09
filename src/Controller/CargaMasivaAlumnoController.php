<?php

namespace App\Controller;

use App\Entity\Alumno;
use App\Entity\Carrera;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Doctrine\Persistence\ManagerRegistry;

class CargaMasivaAlumnoController extends AbstractController
{
    #[Route('/carga/masiva/alumno', name: 'app_carga_masiva_alumno')]
    public function index(): Response
    {
        return $this->render('carga_masiva_alumno/index.html.twig', [
            'controller_name' => 'CargaMasivaAlumnoController',
        ]);
    }

    #[Route('/carga/alumno/archivoimport', name: 'app_carga_masiva_alumno_archivoimport')]
            public function import(Request $request, ManagerRegistry $doctrine)
            {
                if ($request->isMethod('POST')) {
    
                $upload_file=$_FILES['file']['name'];
                $extension=pathinfo($upload_file, PATHINFO_EXTENSION);

                    if('csv' == $extension){
                        $reader = new ReaderCsv();
                    }else if('xlsx' == $extension){
                        $reader = new ReaderXlsx();
                    }else{
                        $reader = new ReaderXlsx();
                    }
                    
                    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
                    $sheetdata=$spreadsheet->getActiveSheet()->toArray();
                    $sheetcount=count($sheetdata);

                    if($sheetcount>1){
                        //$data=array();
                        for($i=1; $i < $sheetcount; $i++) {
                            
                            
                            $alumno = new Alumno ();
                            $em = $doctrine->getManager();

                            $repository = $doctrine->getRepository(Carrera::class);

                            $numero_control=$sheetdata[$i][0];
                            $nombre =$sheetdata[$i][1];
                            $apellido_paterno=$sheetdata[$i][2];
                            $apellido_materno=$sheetdata[$i][3];
                            $carrera=$sheetdata[$i][4];


                            $alumno->setNumeroControl($numero_control);
                            $alumno->setNombreAlumno($nombre);
                            $alumno->setApellidoPaterno($apellido_paterno);
                            $alumno->setApellidoMaterno($apellido_materno);

                           if('ISIC' == $carrera){

                                 $obj_carrera = $repository->findOneBy(['clave_carrera' => 'ISIC']);


                           }else if('IINF' == $carrera){

                                 $obj_carrera = $repository->findOneBy(['clave_carrera' => 'IINF']);
 
                            }else if('ICIV' == $carrera){

                                $obj_carrera = $repository->findOneBy(['clave_carrera' => 'ICIV']);

                           }else if('IGEM' == $carrera){

                            $obj_carrera = $repository->findOneBy(['clave_carrera' => 'IGEM']);

                           }else if('COPU' == $carrera){

                            $obj_carrera = $repository->findOneBy(['clave_carrera' => 'COPU']);

                            }    


                            $alumno->setCarrera($obj_carrera);

                            $em->persist($alumno);
                            $em->flush();  

                        }
                    }

                    return $this->redirectToRoute('app_alumno_index');

                }}
}
