<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Entity\Rh;
use App\Repository\ContractRepository;
use App\Repository\RhRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
     /**
     * @Route("/admin", name="index_admin", methods={"GET"})
     */
    public function index_admin(): Response
    {
        return $this->render('admin/index.html.twig');
    }

     /**
     * @Route("/admin/rhall", name="index_all", methods={"GET"})
     */
    public function indexRh(RhRepository $rhRepository): Response
    {
        return $this->render('admin/all.html.twig', [
            'rhs' => $rhRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/allContract", name="contract_all", methods={"GET"})
     */
    public function indexContract(ContractRepository $contractRepository): Response
    {
        return $this->render('admin/allcontract.html.twig', [
            'contracts' => $contractRepository->findAll(),
        ]);
    }

     /**
     * @Route("/admin/{id}", name="app_adminrh_show", methods={"GET"})
     */
    public function show(Rh $rh): Response
    {
        return $this->render('admin/showrh.html.twig', [
            'rh' => $rh,
        ]);
    }
    /**
     * @Route("/admin/{id}", name="app_adminrh_delete", methods={"POST"})
     */
   public function delete(Request $request, Rh $rh, RhRepository $rhRepository): Response
   {
       if ($this->isCsrfTokenValid('delete'.$rh->getId(), $request->request->get('_token'))) {
           $rhRepository->remove($rh, true);
       }

       return $this->redirectToRoute('index_all', [], Response::HTTP_SEE_OTHER);
   }
    /**
     * @Route("/admin/{id}", name="app_admcontract_show", methods={"GET"})
     */
    public function showcontract(Contract $contract): Response
    {
        return $this->render('admin/showcnt.html.twig', [
            'contract' => $contract,
        ]);
    }
    /**
     * @Route("/admin/{id}", name="app_admcontract_delete", methods={"POST"})
     */
    public function deleteContract(Request $request, Contract $contract, ContractRepository $contractRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $contractRepository->remove($contract, true);
        }

        return $this->redirectToRoute('contract_all', [], Response::HTTP_SEE_OTHER);
    }
}
