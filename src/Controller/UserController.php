<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Form\EditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function PHPUnit\Framework\countOf;
use Dompdf\Dompdf ;
use Dompdf\Options;
/**
 * @Route("/user")
 */
class UserController extends AbstractController
{


     /**
     * @Route("/admin", name="app_user")
     */
    public function index(UserRepository $userRepository ): Response
    {
        return $this->render('admin/user_index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    
    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/{id}/user", name="show_userr", methods={"GET"})
     */
    public function showuser(User $user): Response
    {
        return $this->render('user/show_user.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user,UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->flush();

            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

    }
    /**
     * @Route("/{id}/editStat", name="user_status_edit", methods={"GET", "POST"})
     */
    public function edit_status(Request $request, ManagerRegistry $mg ,User $user, $id,UserRepository $em): Response
    {
        
        $user=$em->find($id);
        if($user->getStatuts()=="desable"){
        $user->setStatuts("enable");}
        elseif($user->getStatuts()=="enable"){
        $user->setStatuts("desable");}
        $em=$mg->getManager();
        $em->persist($user); 
        $em->flush();
            return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/download", name="user_download")
     */
    public function download()
    {
        //definit les option pdf
        $pdfOptions = new Options();
        //police
        $pdfOptions->set('defaultFont', 'Arial');
        // resoudre les prob lié au ssl
        $pdfOptions->setIsRemoteEnabled(true);
        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        // On génère le html
        $html = $this->renderView('user/download.html.twig');

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'user-data-'. $this->getUser()->getId() .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true    //méthode de stream qui va permettre de telechaarger
        ]);

        return new Response();
    }

}
