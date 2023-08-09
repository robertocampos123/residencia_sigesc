<?php

namespace App\Controller;

use App\Entity\Departamento;
use App\Form\DepartamentoType;
use App\Repository\DepartamentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/departamento')]
class DepartamentoController extends AbstractController
{
    #[Route('/', name: 'app_departamento_index', methods: ['GET'])]
    public function index(DepartamentoRepository $departamentoRepository): Response
    {
        $user =  $this->getUser();
        return $this->render('departamento/index.html.twig', [
            'departamentos' => $departamentoRepository->findBy([],['nombre_departamento' => 'ASC']),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_departamento_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DepartamentoRepository $departamentoRepository): Response
    {
        $departamento = new Departamento();
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departamentoRepository->save($departamento, true);

            return $this->redirectToRoute('app_departamento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('departamento/new.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_departamento_show', methods: ['GET'])]
    public function show(Departamento $departamento): Response
    {
        return $this->render('departamento/show.html.twig', [
            'departamento' => $departamento,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_departamento_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departamento $departamento, DepartamentoRepository $departamentoRepository): Response
    {
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $departamentoRepository->save($departamento, true);

            return $this->redirectToRoute('app_departamento_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('departamento/edit.html.twig', [
            'departamento' => $departamento,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_departamento_delete', methods: ['POST'])]
    public function delete(Request $request, Departamento $departamento, DepartamentoRepository $departamentoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$departamento->getId(), $request->request->get('_token'))) {
            $departamentoRepository->remove($departamento, true);
        }

        return $this->redirectToRoute('app_departamento_index', [], Response::HTTP_SEE_OTHER);
    }
}
