<?php

namespace App\Controller;

use App\Entity\Empleado;
use App\Form\EmpleadoType;
use App\Repository\EmpleadoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/empleado')]
class EmpleadoController extends AbstractController
{
    #[Route('/', name: 'app_empleado_index', methods: ['GET'])]
    public function index(EmpleadoRepository $empleadoRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $user =  $this->getUser();
        $query = $empleadoRepository->todosEmpleados();


        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('empleado/index.html.twig', [
            'user' => $user,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_empleado_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmpleadoRepository $empleadoRepository): Response
    {
        $empleado = new Empleado();
        $form = $this->createForm(EmpleadoType::class, $empleado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $empleadoRepository->save($empleado, true);

            return $this->redirectToRoute('app_empleado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('empleado/new.html.twig', [
            'empleado' => $empleado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_empleado_show', methods: ['GET'])]
    public function show(Empleado $empleado): Response
    {
        return $this->render('empleado/show.html.twig', [
            'empleado' => $empleado,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_empleado_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Empleado $empleado, EmpleadoRepository $empleadoRepository): Response
    {
        $form = $this->createForm(EmpleadoType::class, $empleado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $empleadoRepository->save($empleado, true);

            return $this->redirectToRoute('app_empleado_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('empleado/edit.html.twig', [
            'empleado' => $empleado,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_empleado_delete', methods: ['POST'])]
    public function delete(Request $request, Empleado $empleado, EmpleadoRepository $empleadoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$empleado->getId(), $request->request->get('_token'))) {
            $empleadoRepository->remove($empleado, true);
        }

        return $this->redirectToRoute('app_empleado_index', [], Response::HTTP_SEE_OTHER);
    }
}
