<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;




class ProductjsonController extends AbstractController
{
    /**
     * @Route("/productjson", name="product_json")
     */
    public function index(ProductRepository $productRepository, NormalizerInterface $normalizer): Response
    {
        $products=$productRepository->findAll();
        // dd($product);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($products);
        //dd($formatted);

        return new JsonResponse($formatted);
    }

    /**
      * @Route("/deleteProduct", name="delete_Product")
      * @Method("DELETE")
      */

      public function deleteProduct(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        if($product!=null ) {
            $em->remove($product);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("product a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id product invalide.");


    }

    /**
      * @Route("/detailProduct", name="detail_product")
      * @Method("GET")
      */
     public function detailProductAction(Request $request)
     {
         $id = $request->get("id");

         $em = $this->getDoctrine()->getManager();
         $Product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($id);
         $encoder = new JsonEncoder();
         $normalizer = new ObjectNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
             return $object->getNameProduct();$object->getQuantite();$object->getPrix();$object->getTaxe();
         });
         $serializer = new Serializer([$normalizer], [$encoder]);
         $formatted = $serializer->normalize($Product);
         return new JsonResponse($formatted);
     }

       /**
     * @Route("/updateProduct", name="update_product")
     * @Method("PUT")
     */
    public function modifierProduit(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $Product = $this->getDoctrine()->getManager()
                        ->getRepository(Product::class)
                        ->find($request->get("id"));

        $Product->setNameProduct($request->get("name_product"));
        $Product->setQuantite($request->get("quantite"));
        $Product->setPrix($request->get("prix"));
        $Product->setTaxe($request->get("taxe"));


        $em->persist($Product);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Product);
        return new JsonResponse("Produit a ete modifiee avec success.");

    }

     /**
      * @Route("/addproduct", name="add_product")
      * @Method("POST")
      */

      public function ajouterProduit(Request $request)
      {
          $Product = new Product();
          $name_product = $request->query->get("name_product");
          $quantite = $request->query->get("quantite");
          $prix = $request->query->get("prix");
          $taxe = $request->query->get("taxe");
          $em = $this->getDoctrine()->getManager();
 
          $Product->setNameProduct($name_product);
          $Product->setQuantite($quantite);
          $Product->setPrix($prix);
          $Product->setTaxe($taxe);
 
          $em->persist($Product);
          $em->flush();
          $serializer = new Serializer([new ObjectNormalizer()]);
          $formatted = $serializer->normalize($Product);
          return new JsonResponse($formatted);
 
      }

      

}
