<?php

namespace App\Controller;

use App\Entity\Materia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Doctrine\Persistence\ManagerRegistry;

class CargaMasivaAsignaturaController extends AbstractController
{
    #[Route('/carga/masiva/asignatura', name: 'app_carga_masiva_asignatura')]
    public function index(): Response
    {
        return $this->render('carga_masiva_asignatura/index.html.twig', [
            'controller_name' => 'CargaMasivaAsignaturaController',
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
               
                for($i=1; $i < $sheetcount; $i++) {
                    
                    
                    $asignatura = new Materia ();
                    $em = $doctrine->getManager();

                    $em->persist($asignatura);
                    $em->flush();  

                }
            }

            return $this->redirectToRoute('app_alumno_index');

        }}
}
