<?php

namespace App\Controller;

use App\Entity\category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;





class CategoryjsonController extends AbstractController
{
    /**
     * @Route("/categoryjson", name="category_json")
     */
    public function index_cat(CategoryRepository $CategoryRepository, NormalizerInterface $normalizer): Response
    {
        $categories=$CategoryRepository->findAll();
      // dd($product);
      $serializer = new Serializer([new ObjectNormalizer()]);
      $formatted = $serializer->normalize($categories);
      //dd($formatted);

      return new JsonResponse($formatted);
    }
}
