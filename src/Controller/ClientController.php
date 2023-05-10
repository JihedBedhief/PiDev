<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Component\Mailer\Mailer;
use App\Repository\DivisionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\DependencyInjection\Loader\Configurator;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;



/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="app_client_index", methods={"GET"})
     */
    public function index(UserRepository $usser,ClientRepository $clientRepository,Security $security, DivisionRepository $DivisionRepository, Request $request,FlashyNotifier $flashy): Response
    {

        $flashy->info('Welcome User');
        $divisions = $DivisionRepository->findAll();
        $filters = $request->get("divisions");
        $userId = $security->getUser();
        $usr=$usser->find($userId);
        
        $clients = $clientRepository->filterwithdiv($filters,$usr->getId());
        $total = $clientRepository->TotalClients($filters,$usr->getId());

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('client/_content.html.twig',
                    [
                        'clients' => $clients,
                        'divisions' => $divisions,
                        'total' => $total,
                    ]),
            ]);
        }
        return $this->render('client/index.html.twig',
            [
                'clients' => $clients,
                'divisions' => $divisions,
                'total' => $total,
            ]);
    }

    public function __construct(FlashyNotifier $flashy)
{
    $this->flashy = $flashy;
}

    /**
     * @Route("/{id}/editStat", name="client_status_edit", methods={"GET", "POST"})
     */
    public function edit_status(Request $request, ManagerRegistry $mg, ClientRepository $em, $id): Response
    {
        $client = $em->find($id);
        if ($client->getStatuts() == "desable") {
            $client->setStatuts("enable");} 
        elseif ($client->getStatuts() == "enable") {
            $client->setStatuts("desable");}
        $em = $mg->getManager();
        $em->persist($client);
        $em->flush();
        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin", name="client_admin_index", methods={"GET"})
     */
    public function admin(): Response
    {
        return $this->render('admin/client/index.html.twig');
    }

    /**
     * @Route("/admin/get", name="admin_get_client", methods={"GET"})
     */
    public function get_admin(ClientRepository $clientRepository,DivisionRepository $DivisionRepository,Request $request): Response
    {
        $this->flashy->info('Welcome Admin');
        $divisions = $DivisionRepository->findAll();
        $filters = $request->get("divisions");
        $clients = $clientRepository->filterwithdiv($filters);
        $total = $clientRepository->TotalClients($filters);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('admin/client/_content.html.twig',
                    [
                        'clients' => $clients,
                        'divisions' => $divisions,
                        'total' => $total,
                    ]),
            ]);
        }
        return $this->render('admin/client/get.html.twig',
            [
                'clients' => $clients,
                'divisions' => $divisions,
                'total' => $total,
            ]);
    }
    /**
     * @Route("/new", name="app_client_new", methods={"GET", "POST"})
     */
    function new (Request $request, ClientRepository $clientRepository): Response {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->add($client, true);
            $this->addFlash('success', 'Votre Client a été ajouté avec succées');
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_client_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }
    /**
     * @Route("/admin/{id}", name="app_client_show_admin", methods={"GET"})
     */
    public function show_admin(Client $client): Response
    {
        return $this->render('admin/client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_client_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Client $client, ClientRepository $clientRepository,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $divInit=$client->getDivision();
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $newdiv=$client->getDivision();
            if($divInit != $newdiv){
               $email=$client->getEmail();
               $mail=(new Email())
               ->from('pidevmycompany2023@gmail.com')
               ->to($email)
               ->subject('Division update')
               ->text("your division status has been changed !");
               $trasport= new GmailSmtpTransport('pidevmycompany2023@gmail.com','guyuwthwzlzquasf');
               $mailer= new mailer($trasport);
               $mailer->send($mail);
            }
            $clientRepository->add($client, true);
            $flashy->warning('Client modifié avec succes!');
            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_client_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }

   
}
