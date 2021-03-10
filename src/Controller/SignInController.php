<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignInType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class SignInController extends AbstractController
{
    /**
     * @Route("/signin", name="signin")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityMenager, Environment $twig): Response
    {
        $user = new User(); 
        $form = $this->createForm(SignInType::class, $user);
        

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //dd($request);
            $user->setCreationDate(new \DateTime('now'));
            $tab =  $request->request->get('sign_in');
            $pwd = $tab['password'];
            //dd($pwd);
            $user->setPassword($pwd);
            //$user->setPassword($encoder->encodePassword($user, $pwd));
            //$encoder->isPasswordValid($user, $credentials['password']);
            //$var = $encoder->encodePassword($user, $form->getData('password')) . ' ' . $encoder->isPasswordValid($user, $credentials['password']);
            //dd($var);

            $entityMenager->persist($user);
            $entityMenager->flush();
            
            return $this->redirectToRoute('app_login');
        }

        return new Response ($twig->render('main/signIn.html.twig', [
            'sign_form' => $form->createView(),
        ]));
    }
}
