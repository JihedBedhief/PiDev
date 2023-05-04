<?php

namespace App\Controller;

use App\Repository\DivisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DivisionJsonController extends AbstractController
{
    #[Route('/division/json', name: 'app_division_json')]
    public function index(): Response
    {
        return $this->render('division_json/index.html.twig', [
            'controller_name' => 'DivisionJsonController',
        ]);
    }

    #[Route('/listDivision', name:'alldivision')]
    public function ListDivisionJson(DivisionRepository $cat, NormalizerInterface $normalizer): Response
    {
        $categorie = $cat->findAll();
        $js = $normalizer->normalize($categorie, 'json', ['groups' => 'div']);
        return new Response(json_encode($js));
    }

    #[Route('/searchByname', name:'searchByname')]
    public function search(DivisionRepository $cat, NormalizerInterface $normalizer ,Request $request): Response
    {
        $type=$request->get("type");
        $categorie = $cat->findDivisionsByName($type);
        $js = $normalizer->normalize($categorie, 'json', ['groups' => 'div']);
        return new Response(json_encode($js));
    }

    
}
