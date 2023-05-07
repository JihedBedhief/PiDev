<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

/**
 * @Route("/contrat")
 */
class ContratController extends AbstractController
{
    /**
     * @Route("/", name="app_contrat_index", methods={"GET"})
     */
    public function index(ContratRepository $contratRepository): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_contrat_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ContratRepository $contratRepository,\Swift_Mailer $mailer): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->add($contrat, true);
            $message = (new \Swift_Message('Employee contract'))
            ->setFrom('mohamedamine.derouiche@esprit.tn')
            ->setTo($contrat->getEmp()->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/message.twig',
                    ['contract' => $contrat]
                   
                    
                ),
                'text/html'
            );

        $mailer->send($message);

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_contrat_show", methods={"GET"})
     */
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contrat_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contratRepository->add($contrat, true);

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_contrat_delete", methods={"POST"})
     */
    public function delete(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $contratRepository->remove($contrat, true);
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * @Route("/{id}/pdf", name="app_contract_pdf", methods={"GET"})
     */
public function pdf(Contrat $contract): Response
{
    $dompdf = new Dompdf();
    
    $html = $this->renderView('contrat/pdf.html.twig', [
        'contract' => $contract, 
        
    ]);

    $dompdf->loadHtml($html);

    $dompdf->render();

    $response = new Response($dompdf->output());

    $response->headers->set('Content-Type', 'application/pdf');

    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_INLINE,
        'contract.pdf'
    );
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
}
}
