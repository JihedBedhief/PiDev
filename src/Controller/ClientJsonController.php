<?php

namespace App\Controller;

use DateTime;
use App\Entity\Client;
use App\Entity\Division;
use App\Repository\ClientRepository;
use App\Repository\DivisionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ClientJsonController extends AbstractController
{
    #[Route('/client/json', name:'app_client_json')]
    public function index(): Response
    {
        return $this->render('client_json/index.html.twig', [
            'controller_name' => 'ClientJsonController',
        ]);
    }

    #[Route('/listClient', name:'allclient')]
    public function ListClientJson(ClientRepository $cat, NormalizerInterface $normalizer): Response
    {
        $categorie = $cat->findAll();
        $js = $normalizer->normalize($categorie, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($js));
    }

    #[Route('/deleteClientJSON/{id}', name:'DeleteClient')]
    public function deleteClientJSON(Request $request, SerializerInterface $serializer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $Post = $em->getRepository(Client::class)->find($id);
        if ($Post != null) {
            $em->remove($Post);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("client a ete supprimee avec success.");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id Post invalide.");
    }

    #[Route('/addClient', name:'addClient')]
    public function addClient(Request $request, SerializerInterface $serializer, DivisionRepository $di,EntityManagerInterface $entityManager)
    {
        $em = $this->getDoctrine()->getManager();
        $cat = new Client();
       
        $cat->setName($request->get('name'));
        $cat->setEmail($request->get('email'));
        $cat->setVille($request->get('ville'));
        $cat->setCodePostal($request->get('code_postal'));
        $cat->setCin($request->get('cin'));
        $cat->setTelephone($request->get('telephone'));
        $div = $request->get('division');
        $d = $entityManager->getRepository(Division::class)->findDivisionsByName($div);
        $division = $entityManager->getRepository(Division::class)->find($d->getId());
        $cat->setDivision($division);
        $cat->setStatus('actif');
        $cat->setDateAjout( new DateTime('@'.strtotime('now')));
        $em->persist($cat);
        $em->flush();
        $jsonContent = $serializer->serialize($cat, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }],
        );

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;

    }

    
    #[Route('/updateClient/{id}', name:'updateClient')]
    public function updateClient(Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager,$id)
    {
        $em = $this -> getDoctrine()->getManager();
        // $id=$request->get('id');
        $cat = $em->getRepository(Client::class)->find($id);
        $cat->setName($request->get('name'));
        $cat->setEmail($request->get('email'));
        $cat->setVille($request->get('ville'));
        $cat->setCodePostal($request->get('code_postal'));
        $cat->setCin($request->get('cin'));
        $cat->setTelephone($request->get('telephone'));
        $div = $request->get('division');
        $d = $entityManager->getRepository(Division::class)->findDivisionsByName($div);
        $division = $entityManager->getRepository(Division::class)->find($d->getId());
        $cat->setDivision($division);
        $cat->setDateAjout( new DateTime('@'.strtotime('now')));

        $em->flush();
        $jsonContent = $serializer->serialize($cat, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }  ],
        );

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;

    }

}
