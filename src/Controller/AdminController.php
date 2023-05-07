<?php

namespace App\Controller;

use App\Entity\Employees;
use App\Entity\Contrat;
use App\Repository\ContratRepository;
use App\Repository\EmployeesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adm")
 */
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
    public function indexRh(EmployeesRepository $rhRepository): Response
    {
        return $this->render('admin/all.html.twig', [
            'rhs' => $rhRepository->findAll(),
        ]);
    }

     /**
     * @Route("/admin/{id}", name="app_adminrh_show", methods={"GET"})
     */
    public function show(Employees $rh): Response
    {
        return $this->render('admin/showrh.html.twig', [
            'rh' => $rh,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="app_adminrh_delete", methods={"POST"})
     */
    public function delete(Request $request, Employees $employee, EmployeesRepository $employeesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->request->get('_token'))) {
            $employeesRepository->remove($employee, true);
        }

        return $this->redirectToRoute('app_employees_index', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * @Route("/admin/{id}", name="app_admcontract_show", methods={"GET"})
     */
    public function showcontract(Contrat $contrat): Response
    {
        return $this->render('admin/showcnt.html.twig', [
            'contract' => $contrat,
        ]);
    }

    /**
     * @Route("/admin/{id}", name="app_admcontract_delete", methods={"POST"})
     */
    public function deleteC(Request $request, Contrat $contrat, ContratRepository $contratRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $contratRepository->remove($contrat, true);
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
       

}
