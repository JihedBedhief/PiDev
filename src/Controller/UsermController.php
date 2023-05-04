<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UsermController extends AbstractController
{
    /**
     * @Route("/listjson", name="user_json")
     */
    public function index(UserRepository $userRepository, NormalizerInterface $normalizer): Response
    {
        $user=$userRepository->findAll();
        $js=$normalizer->normalize($user,'json',['groups'=>'post:read']);
        // $json=$serializerinterface->normalize($offre,'json',['groups'=>'offre']);
       // dump($user);
      //  die;
        //$formatted= $serializer->normalize($json);
       return new Response(json_encode($js));
    }
    /**
     * @Route("/users/verifier", name="users_m_verifier")
     */
    public function verifM(Request $request,NormalizerInterface $Normalizer,UserRepository $usersRepository,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $email=$request->get('email');
        $mdp=$request->get('password');
        $user = new User();

        $mdpencoded=$userPasswordEncoder->encodePassword($user,$mdp);
        $user=$usersRepository->verifier($email,$mdpencoded);

        $jsoncontentc =$Normalizer->normalize($user,'json',['groups'=>'post:read']);

        $jsonc=json_encode($jsoncontentc);

        if(  $jsonc == "[]" )
        {
            return   new Response("null");
        }
        else{        return new Response($jsonc);
        }

    }



    

    /**
     * @Route("/users/verifier/email", name="users_verifier_email")
     */
    public function verificationemail(Request $request,NormalizerInterface $Normalizer,UserRepository $usersRepository): Response
    {
        $email=$request->get('email');

        $user=$usersRepository->verifieremail($email);

        $jsoncontentc =$Normalizer->normalize($user,'json',['groups'=>'post:read']);

        $jsonc=json_encode($jsoncontentc);

        if(  $jsonc == "[]" )
        {
            return   new Response("null");
        }
        else{        return new Response($jsonc);
        }

    }


    /**
     * @Route("/users/resetpassword", name="users_m_verifier_resetpassword")
     */
    public function resetpassword(Request $request,NormalizerInterface $Normalizer,UserRepository $usersRepository,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $u = $this->getDoctrine()->getManager()
            ->getRepository(User::class)
            ->find($request->get("id"));
        $user = new User();
        $mdp=$request->get("password");
        $mdpencoded =$userPasswordEncoder->encodePassword($user,$mdp);

        $u->setPassword($mdpencoded);
        $em->flush();
        $user=$usersRepository->verifierid($u->getId());

        $jsoncontentc =$Normalizer->normalize($user,'json',['groups'=>'post:read']);

        $jsonc=json_encode($jsoncontentc);

        if(  $jsonc == "[]" )
        {
            return   new Response("null");
        }
        else{        return new Response($jsonc);
        }

    }

    /**
     * @Route("/users/adduser", name="users_m_adduser", methods={"GET","POST"})
     */
    public function addUser(Request $request,NormalizerInterface $Normalizer,UserRepository $usersRepository,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $u = new User();
        $em = $this->getDoctrine()->getManager();

        $u->setEmail($request->get('email'));
        $u->setPassword($request->get('password'));
        $u->setMatricule($request->get('matricule'));
        $u->setAdresse($request->get('adresse'));
        $u->setDomaine($request->get('domaine'));
        $u->setPays($request->get('pays'));
        $u->setTelephone($request->get('telephone'));
        $u->setAbonnement($request->get('abonnement'));
        $u->setNom($request->get('nom')); 
        $u->setStatuts($request->get('statuts'));
                  

        $u->setRoles(['ROLE_USER']);

        $user = new User();
        $mdp=$request->get("password");
        $mdpencoded =$userPasswordEncoder->encodePassword($user,$mdp);

        $u->setPassword($mdpencoded);


        $em->persist($u);
        $em->flush();

        $jsoncontentc =$Normalizer->normalize($u,'json',['groups'=>'post:read']);
        $jsonc=json_encode($jsoncontentc);
        return new Response($jsonc);

    }
   

}
