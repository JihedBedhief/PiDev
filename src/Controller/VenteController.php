<?php

namespace App\Controller;

use App\Entity\Vente;
use App\Form\VenteType;
use Dompdf\Dompdf as Dompdf;
use App\Repository\UserRepository;
use App\Repository\VenteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * @Route("/vente")
 */
class VenteController extends AbstractController
{
    /**
     * @Route("/", name="app_vente_index", methods={"GET"})
     */
    public function index(VenteRepository $venteRepository,UserRepository $usser,Security $security): Response
    {
        $userId = $security->getUser();
        $usr=$usser->find($userId);
        return $this->render('vente/index.html.twig', [
            'ventes' => $venteRepository->getVenteByUserId($usr->getId()),
        ]);
    }
    /**
     * @Route("/admin", name="get_admin_vente", methods={"GET"})
     */

    public function get_admin(VenteRepository $venteRepository): Response
    {
        return $this->render(
            'Admin/vente/get.html.twig',
            [
                'ventes' => $venteRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="app_vente_new", methods={"GET","POST"})
     */
    #[Route('/new', name: 'app_vente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VenteRepository $venteRepository): Response
    {
        $vente = new Vente();
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vente->setPrixTotale(($vente->getPrixUnite() * $vente->getQuantite()) + ($vente->getTaxe()));
            $venteRepository->save($vente, true);

            return $this->redirectToRoute('app_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vente/new.html.twig', [
            'vente' => $vente,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_vente_show", methods={"GET"})
     */
    public function show(Vente $vente): Response
    {
        return $this->render('vente/show.html.twig', [
            'vente' => $vente,
        ]);
    }

    /**
     * @Route("/{id}/admin", name="app_vente_show_admin", methods={"GET"})
     */
    public function show_admin(Vente $vente): Response
    {
        return $this->render('admin/vente/show.html.twig', [
            'vente' => $vente,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_vente_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vente $vente, VenteRepository $venteRepository): Response
    {
        $form = $this->createForm(VenteType::class, $vente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vente->setPrixTotale(($vente->getPrixUnite() * $vente->getQuantite()) + ($vente->getTaxe()));
            $venteRepository->save($vente, true);

            return $this->redirectToRoute('app_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vente/edit.html.twig', [
            'vente' => $vente,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_vente_delete", methods={"POST"})
     */
    public function delete(Request $request, Vente $vente, VenteRepository $venteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vente->getId(), $request->request->get('_token'))) {
            $venteRepository->remove($vente, true);
        }

        return $this->redirectToRoute('app_vente_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/admin", name="app_vente_delete_admin", methods={"POST"})
     */
    public function delete_admin(Request $request, Vente $vente, VenteRepository $venteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vente->getId(), $request->request->get('_token'))) {
            $venteRepository->remove($vente, true);
        }

        return $this->redirectToRoute('get_admin_vente', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/generate/pdf", name="generate_pdf", methods={"GET"})
     */
    public function generatePdf(VenteRepository $venteRepository): Response
    {

        $ventes = $venteRepository->findAll();
        $html =  $this->renderView('vente/ventesPDF.html.twig', ['ventes' => $ventes]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $pdfOutput = $dompdf->output();

        return new Response(
            $pdfOutput,
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
}
