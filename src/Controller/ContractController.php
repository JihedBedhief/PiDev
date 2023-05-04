<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dompdf\Dompdf;


/**
 * @Route("/contract")
 */
class ContractController extends AbstractController
{
    
   /**
     * @Route("/{page}", name="app_contract_index", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function index(Request $request, $page = 1)
    {
        $limit = 4; // the number of items per page
        $offset = ($page - 1) * $limit;
    
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->getRepository(Contract::class)
            ->createQueryBuilder('e')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();
    
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $totalItems = $paginator->count();
        $pagesCount = ceil($totalItems / $limit);
    
        // Add previous and next icons to the pagination links
        $previousPage = $page > 1 ? $page - 1 : null;
        $nextPage = $page < $pagesCount ? $page + 1 : null;
    
        return $this->render('contract/index.html.twig', [
            'contract' => $paginator,
            'currentPage' => $page,
            'pagesCount' => $pagesCount,
            'previousPage' => $previousPage,
            'nextPage' => $nextPage,
        ]);
    }
    
  //  public function index(ContractRepository $contractRepository): Response
  //  {
  //      return $this->render('contract/index.html.twig', [
  //          'contracts' => $contractRepository->findAll(),
  //      ]);
  //  }

    /**
     * @Route("/new", name="app_contract_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ContractRepository $contractRepository,\Swift_Mailer $mailer ): Response
    {
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->add($contract, true);
            $message = (new \Swift_Message('Employee contract'))
            ->setFrom('mohamedamine.derouiche@esprit.tn')
            ->setTo($contract->getEmployer()->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/message.twig',
                    ['contract' => $contract]
                   
                    
                ),
                'text/html'
            );

        $mailer->send($message);

            return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contract/new.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/ContEmp/{id}", name="app_contract_show", methods={"GET"})
     */
    public function show(Contract $contract): Response
    {
        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
        ]);
    
       
    }

    /**
     * @Route("/{id}/edit", name="app_contract_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contract $contract, ContractRepository $contractRepository): Response
    {
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->add($contract, true);

            return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_contract_delete", methods={"POST"})
     */
    public function delete(Request $request, Contract $contract, ContractRepository $contractRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $contractRepository->remove($contract, true);
        }

        return $this->redirectToRoute('app_contract_index', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * @Route("/{id}/pdf", name="app_contract_pdf", methods={"GET"})
     */
public function pdf(Contract $contract): Response
{
    // create new PDF document
    $dompdf = new Dompdf();
    
    // generate HTML content for the document
    $html = $this->renderView('contract/pdf.html.twig', [
        'contract' => $contract, 
        
    ]);

    // load HTML into the PDF document
    $dompdf->loadHtml($html);

    // set paper size and orientation
    

    // render PDF document
    $dompdf->render();

    // create a response object to return the PDF file
    $response = new Response($dompdf->output());
    
    // set content type to application/pdf
    $response->headers->set('Content-Type', 'application/pdf');

    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_INLINE,
        'contract.pdf'
    );
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
}
}
