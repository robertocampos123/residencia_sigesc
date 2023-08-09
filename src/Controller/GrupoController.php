<?php

namespace App\Controller;

use App\Entity\Alumno;
use App\Entity\Calificaciones;
use App\Entity\Grupo;
use App\Form\GrupoType;
use App\Repository\CalificacionesRepository;
use App\Repository\GrupoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/grupo')]
class GrupoController extends AbstractController
{
    #[Route('/', name: 'app_grupo_index', methods: ['GET'])]
    public function index(GrupoRepository $grupoRepository, Request $request, PaginatorInterface $paginator): Response
    {   
        $user =  $this->getUser();
        $query = $grupoRepository->todosGrupo();

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('grupo/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_grupo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GrupoRepository $grupoRepository): Response
    {
        $grupo = new Grupo();
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grupoRepository->save($grupo, true);

            return $this->redirectToRoute('app_grupo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grupo/new.html.twig', [
            'grupo' => $grupo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grupo_show', methods: ['GET'])]
    public function show(Grupo $grupo): Response
    {
        return $this->render('grupo/show.html.twig', [
            'grupo' => $grupo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_grupo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Grupo $grupo, GrupoRepository $grupoRepository): Response
    {
        $form = $this->createForm(GrupoType::class, $grupo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grupoRepository->save($grupo, true);

            return $this->redirectToRoute('app_grupo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grupo/edit.html.twig', [
            'grupo' => $grupo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grupo_delete', methods: ['POST'])]
    public function delete(Request $request, Grupo $grupo, GrupoRepository $grupoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grupo->getId(), $request->request->get('_token'))) {
            $grupoRepository->remove($grupo, true);
        }

        return $this->redirectToRoute('app_grupo_index', [], Response::HTTP_SEE_OTHER);
    }

    


    #[Route('/{id}/lista_alumnos', name: 'app_grupo_alumnos')]
    public function inscribirAlumnos(Grupo $grupo): Response
    {

        $lista_alumnos = $grupo->getAlumnos();

        return $this->render('grupo/lista_alumnos.html.twig', [
            'grupo' => $grupo,
            'alumnos' => $lista_alumnos 
        
        ]);
    }

    #[Route('/{id}/remove_alumno/{alumnoid}', name: 'app_grupo_quit_alumno', methods: ['GET'])]
    //#[ParamConverter("")]
    public function removeAlumno( Grupo $grupo, Alumno $alumno, GrupoRepository $grupoRepository, CalificacionesRepository $calificacionesRepository): Response
    {
        $grupo_id = $grupo->getId();
        $grupo->removeAlumno($alumno);

        $no_alu=  $grupoRepository->noAlumnos($grupo);
        $grupo->setInscritos($no_alu);
        $grupoRepository->save($grupo, true);

        $calificacionesRepository->eliminarCal($grupo, $alumno);

        return $this->redirectToRoute('app_grupo_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/lista_alumnos/import', name: 'app_grupo_alumnos_import',  methods: ['GET', 'POST'])]
    public function import(Request $request,Grupo $grupo, GrupoRepository $grupoRepository, ManagerRegistry $doctrine)
    {

        $grupo_id = $grupo->getId();
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
                    
                    $em = $doctrine->getManager();


                    $repository = $doctrine->getRepository(Alumno::class);
                    $calificaciones = new Calificaciones();

                    $numero_control=$sheetdata[$i][0];           
                    $alumno = $repository->findOneBy(['numero_control' => $numero_control]);

                    $calificaciones->setGrupo($grupo);
                    $calificaciones->setAlumno($alumno);

                    $grupo->addAlumno($alumno);
                    $grupoRepository->save($grupo, true);

                    $em->persist($calificaciones);
                    $em->flush(); 

                }
                $no_alu=  $grupoRepository->noAlumnos($grupo);
                $grupo->setInscritos($no_alu);
                $grupoRepository->save($grupo, true);
                
            }

            return $this->redirectToRoute('app_grupo_alumnos', ['id'=>$grupo_id], Response::HTTP_SEE_OTHER);

        }}
}
