<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\SupplierType;
use App\Repository\SupplierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/supplier")
 */
class SupplierController extends AbstractController
{
    /**
     * @Route("/", name="app_supplier_index", methods={"GET"})
     */
    public function index(SupplierRepository $supplierRepository): Response
    {
        return $this->render('supplier/index.html.twig', [
            'suppliers' => $supplierRepository->findAll(),
        ]);
    }
    /**
     * @Route("/admin/all", name="app_supplier_all", methods={"GET"})
     */
    public function indexSupplier(SupplierRepository $supplierRepository): Response
    {
        return $this->render('admin/show_supplier.html.twig', [
            'suppliers' => $supplierRepository->findAll(),
        ]);
    }
    

    /**
     * @Route("/admin", name="index_admin_supplier", methods={"GET"})
     */
    public function index_admin(): Response
    {
        return $this->render('admin/index.html.twig');
    }


    /**
     * @Route("/new", name="app_supplier_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SupplierRepository $supplierRepository): Response
    {
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplierRepository->add($supplier, true);

            return $this->redirectToRoute('app_supplier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supplier/new.html.twig', [
            'supplier' => $supplier,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_supplier_show", methods={"GET"})
     */
    public function show(Supplier $supplier): Response
    {
        return $this->render('supplier/show.html.twig', [
            'supplier' => $supplier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_supplier_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Supplier $supplier, SupplierRepository $supplierRepository): Response
    {
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $supplierRepository->add($supplier, true);

            return $this->redirectToRoute('app_supplier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('supplier/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_supplier_delete", methods={"POST"})
     */
    public function delete(Request $request, Supplier $supplier, SupplierRepository $supplierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$supplier->getId(), $request->request->get('_token'))) {
            $supplierRepository->remove($supplier, true);
        }

        return $this->redirectToRoute('app_supplier_index', [], Response::HTTP_SEE_OTHER);
    }
}
