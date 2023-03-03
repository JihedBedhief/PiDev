<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{

    /**
     * @Route("/dashboard", name="display_dashboard")
     */
    public function indexDashbord(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
    // /**
    //  * @Route("/", name="app_product_index", methods={"GET"})
    //  */
    // public function index(ProductRepository $productRepository): Response
    // {
    //     return $this->render('product/index.html.twig', [
    //         'products' => $productRepository->findAll(),
    //     ]);
    // }

      /**
      * @Route("/", name="app_product_index", methods={"GET"})
      */    
    public function indexx(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        // récupérer les paramètres de la requête
        $limit = $request->query->getInt('limit', 5);
        $page = $request->query->getInt('page', 1);
        $q = $request->query->get('q');
    
        // construire la requête pour récupérer les utilisateurs
        $queryBuilder = $productRepository->createQueryBuilder('t');
        $queryBuilder->orderBy('t.id', 'ASC');
        if ($q) {
            $queryBuilder->where('t.name_product LIKE :q')
                         ->setParameter('q', '%'.$q.'%');
        }
        $query = $queryBuilder->getQuery();
    
        // paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $page,
            $limit
        );
    
        return $this->render('product/indexx.html.twig', [
            'products' => $pagination,
            'q' => $q,
        ]);
    }

    /**
     * @Route("/admin", name="admin_index_prod", methods={"GET"})
     */
    public function admin(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    // /**
    //  * @Route("/admin/all", name="app_product_admin_index", methods={"GET"})
    //  */
    // public function index_admin(ProductRepository $productRepository): Response
    // {
    //     return $this->render('admin/index_product.html.twig', [
    //         'products' => $productRepository->findAll(),
    //     ]);
    // }

   /**
      * @Route("/admin/all", name="app_product_admin_index", methods={"GET"})
      */    
      public function index_admin(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response
      {
          $entityManager = $this->getDoctrine()->getManager();
      
          // récupérer les paramètres de la requête
          $limit = $request->query->getInt('limit', 5);
          $page = $request->query->getInt('page', 1);
          $q = $request->query->get('q');
      
          // construire la requête pour récupérer les utilisateurs
          $queryBuilder = $productRepository->createQueryBuilder('t');
          $queryBuilder->orderBy('t.id', 'ASC');
          if ($q) {
              $queryBuilder->where('t.name_product LIKE :q')
                           ->setParameter('q', '%'.$q.'%');
          }
          $query = $queryBuilder->getQuery();
      
          // paginer les résultats
          $pagination = $paginator->paginate(
              $query,
              $page,
              $limit
          );
      
          return $this->render('admin/indexx_product.html.twig', [
              'products' => $pagination,
              'q' => $q,
          ]);
      }

    /**
     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductRepository $productRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $imageFile */
           
    $imageFile = $form->get('image')->getData();

    // if($imageFile){
         $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
         $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
         try {
             $imageFile->move(
                 $this->getParameter('images_directory'),
                 $newFilename
             );
         } catch (FileException $e) {
             // ... handle exception if something happens during file upload
         }
         $product->setImage($newFilename);
            $productRepository->add($product, true);
           
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
    /**
     * @Route("/{id}", name="app_cat_show", methods={"GET"})
     */
    public function showcat(Category $category): Response
    {
        return $this->render('product/show.html.twig', [
            'cat' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }

   
}
