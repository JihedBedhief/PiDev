<?php

namespace App\Controller;

use App\Entity\Division;
use App\Form\DivisionType;
use App\Repository\DivisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/division")
 */
class DivisionController extends AbstractController
{
    /**
     * @Route("/", name="app_division_index", methods={"GET"})
     */
    public function index(DivisionRepository $divisionRepository,SerializerInterface $serializerInterface): Response
    {
        $divisions=$divisionRepository->findAll();
        return $this->render('division/index.html.twig', [
            'divisions' => $divisions,
        ]);
    }

     /**
     * @Route("/admin", name="app_division_index_admin", methods={"GET"})
     */
    public function index_admin(): Response
    {
        return $this->render('admin/division/index.html.twig');
    }

    /**
     * @Route("/admin/get", name="admin_get_division", methods={"GET"})
     */
    public function get_admin(DivisionRepository $divisionRepository): Response
    {
        $divisions=$divisionRepository->findAll();
        return $this->render('admin/division/get.html.twig', [
            'divisions' => $divisions,
        ]);
    }
    
    /**
     * @Route("/new", name="app_division_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DivisionRepository $divisionRepository): Response
    {
        $division = new Division();
        $form = $this->createForm(DivisionType::class, $division);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $divisionRepository->add($division, true);

            return $this->redirectToRoute('app_division_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('division/new.html.twig', [
            'division' => $division,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_division_show", methods={"GET"})
     */
    public function show(Division $division): Response
    {
        return $this->render('division/show.html.twig', [
            'division' => $division,
        ]);
    }
     /**
     * @Route("/admin/{id}", name="app_division_show_admin", methods={"GET"})
     */
    public function show_admin(Division $division): Response
    {
        return $this->render('admin/division/show.html.twig', [
            'division' => $division,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_division_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Division $division, DivisionRepository $divisionRepository): Response
    {
        $form = $this->createForm(DivisionType::class, $division);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $divisionRepository->add($division, true);

            return $this->redirectToRoute('app_division_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('division/edit.html.twig', [
            'division' => $division,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_division_delete", methods={"POST"})
     */
    public function delete(Request $request, Division $division, DivisionRepository $divisionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$division->getId(), $request->request->get('_token'))) {
            $divisionRepository->remove($division, true);
        }

        return $this->redirectToRoute('app_division_index', [], Response::HTTP_SEE_OTHER);
    }


}
