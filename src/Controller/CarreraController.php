<?php

namespace App\Controller;

use App\Entity\Carrera;
use App\Form\CarreraType;
use App\Repository\CarreraRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/carrera')]
class CarreraController extends AbstractController
{
    #[Route('/', name: 'app_carrera_index', methods: ['GET'])]
    public function index(CarreraRepository $carreraRepository): Response
    {
        $user = $this->getUser();
        return $this->render('carrera/index.html.twig', [
            'carreras' => $carreraRepository->findBy([],['nombre_carrera' => 'ASC']),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_carrera_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CarreraRepository $carreraRepository): Response
    {
        $carrera = new Carrera();
        $form = $this->createForm(CarreraType::class, $carrera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carreraRepository->save($carrera, true);

            return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carrera/new.html.twig', [
            'carrera' => $carrera,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carrera_show', methods: ['GET'])]
    public function show(Carrera $carrera): Response
    {
        return $this->render('carrera/show.html.twig', [
            'carrera' => $carrera,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carrera_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carrera $carrera, CarreraRepository $carreraRepository): Response
    {
        $form = $this->createForm(CarreraType::class, $carrera);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carreraRepository->save($carrera, true);

            return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carrera/edit.html.twig', [
            'carrera' => $carrera,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carrera_delete', methods: ['POST'])]
    public function delete(Request $request, Carrera $carrera, CarreraRepository $carreraRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrera->getId(), $request->request->get('_token'))) {
            $carreraRepository->remove($carrera, true);
        }

        return $this->redirectToRoute('app_carrera_index', [], Response::HTTP_SEE_OTHER);
    }
}
