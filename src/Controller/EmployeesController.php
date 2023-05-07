<?php

namespace App\Controller;

use App\Entity\Employees;
use App\Form\EmployeesType;
use App\Form\RechercheType;
use App\Repository\EmployeesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/employees")
 */
class EmployeesController extends AbstractController
{

    /**
     * @Route("/", name="app_employees_index", methods={"GET"})
     */
    public function index(EmployeesRepository $contratRepository): Response
    {
        return $this->render('employees/index.html.twig', [
            'employees' => $contratRepository->findAll(),
        ]);
    }

   
    // /**
    //  * @Route("/{page}", name="app_employees_index", requirements={"page"="\d+"}, defaults={"page"=1})
    //  */
    // public function index(Request $request, $page = 1)
    // {
    //     $limit = 4; // the number of items per page
    //     $offset = ($page - 1) * $limit;
    
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $query = $entityManager->getRepository(Employees::class)
    //         ->createQueryBuilder('e')
    //         ->setFirstResult($offset)
    //         ->setMaxResults($limit)
    //         ->getQuery();
    
    //     $paginator = new Paginator($query, $fetchJoinCollection = true);
    //     $totalItems = $paginator->count();
    //     $pagesCount = ceil($totalItems / $limit);
    
    //     // Add previous and next icons to the pagination links
    //     $previousPage = $page > 1 ? $page - 1 : null;
    //     $nextPage = $page < $pagesCount ? $page + 1 : null;
    
    //     return $this->render('Employees/index.html.twig', [
    //         'employees' => $paginator,
    //         'currentPage' => $page,
    //         'pagesCount' => $pagesCount,
    //         'previousPage' => $previousPage,
    //         'nextPage' => $nextPage,
    //     ]);
    // }
       

    /**
     * @Route("/new", name="app_employees_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EmployeesRepository $employeesRepository): Response
    {
        $employee = new Employees();
        $form = $this->createForm(EmployeesType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeesRepository->add($employee, true);

            return $this->redirectToRoute('app_contrat_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employees/new.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_employees_show", methods={"GET"})
     */
    public function show(Employees $employee): Response
    {
        return $this->render('employees/show.html.twig', [
            'employee' => $employee,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_employees_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Employees $employee, EmployeesRepository $employeesRepository): Response
    {
        $form = $this->createForm(EmployeesType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employeesRepository->add($employee, true);

            return $this->redirectToRoute('app_employees_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employees/edit.html.twig', [
            'employee' => $employee,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_employees_delete", methods={"POST"})
     */
    public function delete(Request $request, Employees $employee, EmployeesRepository $employeesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employee->getId(), $request->request->get('_token'))) {
            $employeesRepository->remove($employee, true);
        }

        return $this->redirectToRoute('app_employees_index', [], Response::HTTP_SEE_OTHER);
    }

    

     /**
     * @Route("/admin/findById", name="findByIdad",methods={"GET", "POST"})
     */
    public function  findByNsc(EmployeesRepository $repo, Request $request) : Response
    { $student = New Employees() ;
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
    
            $repository = $this->getDoctrine()->getRepository(Employees::class);
            $queryBuilder = $repository->createQueryBuilder('e')
                ->select('e.nom, e.prenom, e.cin,e.email,e.phoneNum')
                ->where('e.idComp = :id')
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

        $repository = $this->getDoctrine()->getRepository(Employees::class);
        $queryBuilder = $repository->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.idComp = :id')
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

 /**
     * @Route("/admin/filtre", name="filtre",methods={"GET", "POST"})
     */
    public function filreRh()
    {
            $repository = $this->getDoctrine()->getRepository(Employees::class);
            $queryBuilder = $repository->createQueryBuilder('e')
                ->select('e.nom, e.prenom, e.cin, e.email, e.phoneNum')
                ->orderBy('e.nom');
            $filtre = $queryBuilder->getQuery()->getResult();
       
    
        return $this->render('admin/filtre_employees.html.twig', [
            'filtre' => $filtre,
        ]);
    }
       
    
}
