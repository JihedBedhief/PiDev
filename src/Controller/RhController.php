<?php

namespace App\Controller;

use App\Entity\Rh;
use App\Form\RhType;
use App\Form\RechercheType;
use App\Form\RhcompanyType;
use App\Repository\RhRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * @Route("/rh")
 */
class RhController extends AbstractController
{
    /**
     * @Route("/", name="app_rh_index", methods={"GET"})
     */
    public function index(RhRepository $rhRepository): Response
    {
        return $this->render('rh/index.html.twig', [
            'rhs' => $rhRepository->findAll(),
        ]);
    }
    

    /**
     * @Route("/new", name="app_rh_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RhRepository $rhRepository): Response
    {
        $rh = new Rh();
        $form = $this->createForm(RhType::class, $rh);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rhRepository->add($rh, true);

            return $this->redirectToRoute('app_contract_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rh/new.html.twig', [
            'rh' => $rh,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_rh_show", methods={"GET"})
     */
    public function show(Rh $rh): Response
    {
        return $this->render('rh/show.html.twig', [
            'rh' => $rh,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_rh_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Rh $rh, RhRepository $rhRepository): Response
    {
        $form = $this->createForm(RhType::class, $rh);
      
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rhRepository->add($rh, true);

            return $this->redirectToRoute('app_rh_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rh/edit.html.twig', [
            'rh' => $rh,
            'form' => $form,
        ]);
    }

  /**
     * @Route("/{id}", name="app_rh_delete", methods={"POST"})
     */


   public function delete(Request $request, Rh $rh, RhRepository $rhRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rh->getId(), $request->request->get('_token'))) {
            $rhRepository->remove($rh, true);
        }

        return $this->redirectToRoute('app_rh_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/admin/findById", name="findByIdad",methods={"GET", "POST"})
     */
    public function  findByNsc(RhRepository $repo, Request $request) : Response
    { $student = New Rh() ;
        $students= $repo->findAll();
        $form = $this->createForm(RechercheType::class, $student);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $nsc = $form['id']->getData() ;
            $student = $repo->findOneByNSC($nsc);
            return $this->renderForm("admin/search.html.twig",
                ["f"=>$form,"student"=>$student]);
        }
        return $this->renderForm("admin/index.html.twig",
            ["f"=>$form,"students"=>$students]);

    }
    /**
     * @Route("/admin/search", name="adminn_search",methods={"GET", "POST"})
     */
    public function employeesList(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('id', IntegerType::class)
            ->add('submit', SubmitType::class, ['label' => 'Search'])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $id = $data['id'];
    
            $repository = $this->getDoctrine()->getRepository(Rh::class);
            $queryBuilder = $repository->createQueryBuilder('e')
                ->select('e.name, e.fonction, e.phone_number, e.email, e.departement')
                ->where('e.id_company = :id')
                ->setParameter('id', $id);
    
            $employees = $queryBuilder->getQuery()->getResult();
        } else {
            $employees = null;
        }
    
        return $this->render('admin/search.html.twig', [
            'form' => $form->createView(),
            'employees' => $employees,
        ]);
    }


 /**
     * @Route("/admin/count", name="countnbr",methods={"GET", "POST"})
     */
public function countRh(Request $request)
{
    $form = $this->createFormBuilder()
        ->add('id', IntegerType::class)
        ->add('submit', SubmitType::class, ['label' => 'Count'])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $id = $data['id'];

        $repository = $this->getDoctrine()->getRepository(Rh::class);
        $queryBuilder = $repository->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.id_company = :id')
            ->setParameter('id', $id);

        $count = $queryBuilder->getQuery()->getSingleScalarResult();
    } else {
        $count = null;
    }

    return $this->render('admin/count_employees.html.twig', [
        'form' => $form->createView(),
        'count' => $count,
    ]);
}
 
   /* public function ShowparId(RhRepository $rhRepository, $id): Response
    {
        $rhList = $rhRepository->findByCompanyId($id);
        return $this->render('admin/showbycompany.html.twig', [
            'rhList' => $rhList,
        ]);

        
    }*/

   
}
