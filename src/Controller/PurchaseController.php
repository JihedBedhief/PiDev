<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\PurchaseType;
use App\Repository\PurchaseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @Route("/purchase")
 */
class PurchaseController extends AbstractController
{
    /**
     * @Route("/", name="app_purchase_index", methods={"GET"})
     */
    public function index (Request $request, PaginatorInterface $paginator) // Nous ajoutons les paramètres requis
    {
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $donnees = $this->getDoctrine()->getRepository(Purchase::class)->findAll();

        $purchases = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4// Nombre de résultats par page
        );
        
        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchases,
        ]);
    }
    /**
 * @Route("/{id}/total", name="purchase_total", methods={"GET", "POST"})
 */
public function purchaseTotal(Purchase $purchase)
{
    $total = $purchase->getUnitPrice() * $purchase->getQte() + ($purchase->getUnitPrice() * $purchase->getQte() * $purchase->getTaxeRate() / 100);

    return $this->render('purchase/total.html.twig', [
        'purchase' => $purchase,
        'total' => $total,
    ]);
}
    /**
     * @Route("/admin/all", name="app_purchase_all", methods={"GET"})
     */
    public function index_purchase(PurchaseRepository $purchaseRepository): Response
    {
        return $this->render('admin/show_puchase.html.twig', [
            'purchases' => $purchaseRepository->findAll(),
        ]);
    }
    /**
     * @Route("/admin", name="index_admin", methods={"GET"})
     */
    public function index_admin(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/new", name="app_purchase_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PurchaseRepository $purchaseRepository): Response
    {
        $purchase = new Purchase();
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $purchaseRepository->add($purchase, true);

            return $this->redirectToRoute('app_purchase_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('purchase/new.html.twig', [
            'purchase' => $purchase,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}/pdf", name="app_purchase_pdf", methods={"GET"})
     */
public function pdf(Purchase $purchase): Response
{
    // create new PDF document
    $dompdf = new Dompdf();
    
    // generate HTML content for the document
    $html = $this->renderView('purchase/pdf.html.twig', [
        'purchase' => $purchase, 
        
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
        'purchase.pdf'
    );
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
}

    /**
     * @Route("/{id}", name="app_purchase_show", methods={"GET"})
     */

        public function show(Purchase $purchase): Response
        {
            return $this->render('purchase/show.html.twig', [
                'purchase' => $purchase,
            ]);
        }
    

    /**
     * @Route("/{id}/edit", name="app_purchase_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Purchase $purchase, PurchaseRepository $purchaseRepository): Response
    {
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $purchaseRepository->add($purchase, true);

            return $this->redirectToRoute('app_purchase_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('purchase/edit.html.twig', [
            'purchase' => $purchase,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_purchase_delete", methods={"POST"})
     */
    public function delete(Request $request, Purchase $purchase, PurchaseRepository $purchaseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$purchase->getId(), $request->request->get('_token'))) {
            $purchaseRepository->remove($purchase, true);
        }

        return $this->redirectToRoute('app_purchase_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/admin/search", name="adminn",methods={"GET", "POST"})
     */
    public function List(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('id', IntegerType::class)
            ->add('submit', SubmitType::class, ['label' => 'Search'])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $id = $data['id'];
    
            $repository = $this->getDoctrine()->getRepository(Purchase::class);
            $queryBuilder = $repository->createQueryBuilder('e')
                ->select('e.product, e.qte, e.unitPrice, e.puchaseDate, e.taxeRate')
                ->where('e.id = :id')
                ->setParameter('id', $id);
    
            $employees = $queryBuilder->getQuery()->getResult();
        } else {
            $employees = null;
        }
    
        return $this->render('admin/searchpuchase.html.twig', [
            'form' => $form->createView(),
            'employees' => $employees,
        ]);
    }

}
