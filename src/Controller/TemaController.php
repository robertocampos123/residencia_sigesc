<?php

namespace App\Controller;

use App\Entity\Materia;
use App\Entity\Tema;
use App\Form\TemaType;
use App\Repository\TemaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tema')]
class TemaController extends AbstractController
{
    #[Route('/', name: 'app_tema_index', methods: ['GET'])]
    public function index(TemaRepository $temaRepository): Response
    {

       // $materia = $grupo->getMateria();
       // $repository = $doctrine->getRepository(Tema::class);
       // $temas = $repository->findBy(['materia'=>$materia],['numero_unidad'=>'ASC']);

        return $this->render('tema/index.html.twig', [
            'temas' => $temaRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_tema_new', methods: ['GET', 'POST'])]
    public function new(Materia $materia, Request $request, TemaRepository $temaRepository): Response
    {
        $tema = new Tema();
        $form = $this->createForm(TemaType::class, $tema);
        $form->handleRequest($request);
        $id = $materia->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $tema->setMateria($materia);
            $temaRepository->save($tema, true);

            return $this->redirectToRoute('app_materia_temario', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tema/new.html.twig', [
            'tema' => $tema,
            'form' => $form,
            'materia' => $materia
        ]);
    }

    #[Route('/{id}', name: 'app_tema_show', methods: ['GET'])]
    public function show(Tema $tema): Response
    {
        return $this->render('tema/show.html.twig', [
            'tema' => $tema,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tema_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tema $tema, TemaRepository $temaRepository): Response
    {
        $form = $this->createForm(TemaType::class, $tema);
        $form->handleRequest($request);
        $id = $tema->getMateria()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $temaRepository->save($tema, true);

            return $this->redirectToRoute('app_materia_temario', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tema/edit.html.twig', [
            'tema' => $tema,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tema_delete', methods: ['POST'])]
    public function delete(Request $request, Tema $tema, TemaRepository $temaRepository): Response
    {
        
        $id = $tema->getMateria()->getId();
        
        if ($this->isCsrfTokenValid('delete'.$tema->getId(), $request->request->get('_token'))) {
            $temaRepository->remove($tema, true);
            
        }

        return $this->redirectToRoute('app_materia_temario', ['id'=>$id], Response::HTTP_SEE_OTHER);
    }
}
