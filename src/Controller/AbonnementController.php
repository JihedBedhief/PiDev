<?php

namespace App\Controller;


use App\Entity\Abonnement;
use App\Entity\User;
use App\Form\Abonnement1Type;
use App\Repository\UserRepository;
use App\Repository\AbonnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/abonnement")
 */
class AbonnementController extends AbstractController
{
    /**
     * @Route("/", name="app_abonnement_index", methods={"GET"})
     */
    public function index(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
        ]);
    }

   

    /**
     * @Route("/new", name="app_abonnement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AbonnementRepository $abonnementRepository): Response
    {
        $abonnement = new Abonnement();
        $form = $this->createForm(Abonnement1Type::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abonnementRepository->add($abonnement, true);
            $this->addFlash('success', 'Votre abbonnement a été ajouté avec succées!');
            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/new.html.twig', [
            'abonnement' => $abonnement,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_abonnement_show", methods={"GET"})
    
     */
    
    public function show(Abonnement $abonnement): Response
    {
        
        return $this->render('abonnement/show.html.twig', [
            'abonnement' => $abonnement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_abonnement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {
        $form = $this->createForm(Abonnement1Type::class, $abonnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $abonnementRepository->add($abonnement, true);

            return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('abonnement/edit.html.twig', [
            'abonnement' => $abonnement,
            'f' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_abonnement_delete", methods={"POST"})
     */
    public function delete(Request $request, Abonnement $abonnement, AbonnementRepository $abonnementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$abonnement->getId(), $request->request->get('_token'))) {
            $abonnementRepository->remove($abonnement, true);
        }

        return $this->redirectToRoute('app_abonnement_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/stat", name="user_stat")
     */
    // public function statistic(AbonnementRepository $abonnementRepository)
    // {


    //     $abonnementtype = ["vip","classic"];
    //     $VipCount=count($userRepository->findByVip());
    //     $ClassicCount=count($abonnementRepository->findByClassic());
    //     $abonnementCount = [$VipCount,$ClassicCount];

    //     dump($VipCount);
    //     dump($ClassicCount);
    //     dump($abonnementCount);


    //     return $this->render('Back/user/stat.html.twig', [
    //         'abonnementtype'=>json_encode($abonnementtype),
    //             'abonnementCount'=>json_encode($abonnementCount),
    //         ]
    //     );
    // }

     /**
     * @Route("/statistiques", name="statistiques")
     */
    public function statistiques(AbonnementRepository $repAbon, UserRepository $repUser)
    { $users = $repUser->findAll();

        $userNom = [];
        
        $userCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($users as $user){
            $userNom[] = $user->getNom();
            $userCount[] = count($user->getAbonnement());
        }

        // On va chercher le nombre d'annonces publiées par date
        $abonnement = $repAbon->countByUser();

        $abonnementCount = [];

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
      
        return $this->render('abonnement/stats.html.twig', [
            'userNom' => json_encode($userNom),

            'userCount' => json_encode($userCount),

            'abonnementCount' => json_encode($userCount),
        ]);
    }
}
