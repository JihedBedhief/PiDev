<?php

namespace App\Controller;

use App\Entity\PointVente;

use App\Form\PointVenteType;
use App\Repository\PointVenteRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;
/**
 * @Route("/pointVente")
 */
class PointVenteController extends AbstractController
{
    /**
     * @Route("/", name="app_point_vente_index", methods={"GET"})
     */
    public function index(PointVenteRepository $pointVenteRepository): Response
    {
        return $this->render('pointvente/get.html.twig', [
            'point_ventes' => $pointVenteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/admin", name="get_admin_pointvente", methods={"GET"})
     */
    public function get_admin(PointVenteRepository $pointVenteRepository):Response
    {
        return $this->render('Admin/pointvente/get.html.twig', [
            'point_ventes' => $pointVenteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="app_point_vente_new", methods={"POST","GET"})
     */
    public function new(Request $request, PointVenteRepository $pointVenteRepository,MailerInterface $mailer,TexterInterface $texter): Response
    {
        $pointVente = new PointVente();
        $form = $this->createForm(PointVenteType::class, $pointVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sms = new SmsMessage(
                '+216'.$pointVente->getTelephone(),
                'Bonjour '.$pointVente->getName().', votre point de vente a été ajouté avec succées !'
            );
    
             $texter->send($sms);
            $email = (new Email())
            ->from('anas.basta2023@gmail.com')
            ->to($pointVente->getEmail())
            ->subject('Point de vente')
            ->text($pointVente->getName().' Votre point de vente ajoutée avec succées, email envoyé !');
    
        $mailer->send($email);
       
      
      
        //display  
            $pointVenteRepository->save($pointVente, true);
           $this->addFlash('success', 'Votre point de vente a été ajouté avec succées, email and sms notifications sent !');
                  return $this->redirectToRoute('app_point_vente_index', [], Response::HTTP_SEE_OTHER);
        }

      

        return $this->renderForm('pointvente/new.html.twig', [
            'point_vente' => $pointVente,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_point_vente_show", methods={"GET"})
     */
    public function show(PointVente $pointVente): Response
    {
        
        return $this->render('pointvente/show.html.twig', [
            'point_vente' => $pointVente,
        ]);
    }

    /**
     * @Route("/{id}/admin", name="app_point_vente_show_admin", methods={"GET"})
     */
    public function show_admin(PointVente $pointVente): Response
    {
    return $this->render('admin/pointvente/show.html.twig', [
        'point_vente' => $pointVente,
    ]);}
   

    /**
     * @Route("/{id}/edit", name="app_point_vente_edit", methods={"POST","GET"})
     */
    public function edit(Request $request, PointVente $pointVente, PointVenteRepository $pointVenteRepository): Response
    {
        $form = $this->createForm(PointVenteType::class, $pointVente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pointVenteRepository->save($pointVente, true);

            return $this->redirectToRoute('app_point_vente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pointvente/edit.html.twig', [
            'point_vente' => $pointVente,
            'f' => $form,
        ]);
    }


    /**
     * @Route("/{id}", name="app_point_vente_delete", methods={"POST"})
     */
    public function delete(Request $request, PointVente $pointVente, PointVenteRepository $pointVenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pointVente->getId(), $request->request->get('_token'))) {
            $pointVenteRepository->remove($pointVente, true);
        }

        return $this->redirectToRoute('app_point_vente_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/admin", name="app_point_vente_delete_admin", methods={"POST"})
     */
    public function delete_pt_admin(Request $request, PointVente $pointVente, PointVenteRepository $pointVenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pointVente->getId(), $request->request->get('_token'))) {
            $pointVenteRepository->remove($pointVente, true);
        }

        return $this->redirectToRoute('get_admin_pointvente', [], Response::HTTP_SEE_OTHER);
    }
    
}
