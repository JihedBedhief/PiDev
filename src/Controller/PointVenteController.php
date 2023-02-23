<?php

namespace App\Controller;

use App\Entity\PointVente;
use App\Form\PointVenteType;
use App\Repository\PointVenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pointVente')]
class PointVenteController extends AbstractController
{
    #[Route('/', name: 'app_point_vente_index', methods: ['GET'])]
    public function index(PointVenteRepository $pointVenteRepository): Response
    {
        return $this->render('point_vente/index.html.twig', [
            'point_ventes' => $pointVenteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_point_vente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PointVenteRepository $pointVenteRepository): Response
    {
        $pointVente = new PointVente();
        $form = $this->createForm(PointVenteType::class, $pointVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pointVenteRepository->save($pointVente, true);

            return $this->redirectToRoute('app_point_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('point_vente/new.html.twig', [
            'point_vente' => $pointVente,
            'f' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_point_vente_show', methods: ['GET'])]
    public function show(PointVente $pointVente): Response
    {
        return $this->render('point_vente/show.html.twig', [
            'point_vente' => $pointVente,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_point_vente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PointVente $pointVente, PointVenteRepository $pointVenteRepository): Response
    {
        $form = $this->createForm(PointVenteType::class, $pointVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pointVenteRepository->save($pointVente, true);

            return $this->redirectToRoute('app_point_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('point_vente/edit.html.twig', [
            'point_vente' => $pointVente,
            'f' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_point_vente_delete', methods: ['POST'])]
    public function delete(Request $request, PointVente $pointVente, PointVenteRepository $pointVenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pointVente->getId(), $request->request->get('_token'))) {
            $pointVenteRepository->remove($pointVente, true);
        }

        return $this->redirectToRoute('app_point_vente_index', [], Response::HTTP_SEE_OTHER);
    }
}
