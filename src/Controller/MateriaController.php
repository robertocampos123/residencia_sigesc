<?php

namespace App\Controller;

use App\Entity\Materia;
use App\Entity\Tema;
use App\Form\MateriaType;
use App\Repository\MateriaRepository;
use App\Repository\TemaRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/materia')]
class MateriaController extends AbstractController
{
    #[Route('/', name: 'app_materia_index', methods: ['GET'])]
    public function index(MateriaRepository $materiaRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $query = $materiaRepository->queryMateria();

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('materia/index.html.twig', [
            'pagination' => $pagination,
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_materia_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MateriaRepository $materiaRepository): Response
    {
        $materium = new Materia();
        $form = $this->createForm(MateriaType::class, $materium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materiaRepository->save($materium, true);

            return $this->redirectToRoute('app_materia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materia/new.html.twig', [
            'materium' => $materium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materia_show', methods: ['GET'])]
    public function show(Materia $materium): Response
    {
        return $this->render('materia/show.html.twig', [
            'materium' => $materium,
        ]);
    }

    #[Route('/{id}/temario', name: 'app_materia_temario', methods: ['GET'])]
    public function temario(Materia $materium, ManagerRegistry $doctrine): Response
    {

        $tematicas = $doctrine->getRepository(Tema::class);
        $unidades = $tematicas->findBy(['materia'=>$materium],['numero_unidad'=>'ASC']);

        return $this->render('materia/temario.html.twig', [
            'asignatura' => $materium,
            'unidades' => $unidades,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_materia_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materia $materium, MateriaRepository $materiaRepository): Response
    {
        $form = $this->createForm(MateriaType::class, $materium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materiaRepository->save($materium, true);

            return $this->redirectToRoute('app_materia_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materia/edit.html.twig', [
            'materium' => $materium,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materia_delete', methods: ['POST'])]
    public function delete(Request $request, Materia $materium, MateriaRepository $materiaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materium->getId(), $request->request->get('_token'))) {
            $materiaRepository->remove($materium, true);
        }

        return $this->redirectToRoute('app_materia_index', [], Response::HTTP_SEE_OTHER);
    }
}
