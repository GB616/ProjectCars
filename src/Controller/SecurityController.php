<?php

namespace App\Controller;

use App\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $previousPage = $request->headers->get('referer');
         if ($this->getUser()) 
            // return $this->redirectToRoute('homepage');
             return $this->redirectToRoute($previousPage);
         

         $error = $authenticationUtils->getLastAuthenticationError();
         $lastUsername = $authenticationUtils->getLastUsername();
         

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'previous_page' => $previousPage]);
    }

    /**
     * @Route("/loginadmin", name="app_admin_login")
     */
    public function loginAdmin(AuthenticationUtils $authenticationUtils): Response
    {   
        
        
        if ($this->getUser()) 
            return $this->redirectToRoute('homepage');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        //dd($this->getUser());

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
         // controller can be blank: it will never be executed!
         throw new \Exception('Don\'t forget to activate logout in security.yaml');
       
    }
}
