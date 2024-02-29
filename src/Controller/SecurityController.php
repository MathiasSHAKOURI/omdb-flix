<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    /**
    * @Route("/admin/index", name="app_admin", methods={"GET"})
    * @isGranted("BACK_OFFICE_ACCESS")
    */
    public function admin(): Response
    {
        return $this->render('back/index.admin.html.twig');
    }

    /**
     * @Route("/login", name="app_security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
    * @Route("/logout", name="app_security_logout")
    */
    public function logout(): void
    {
    }

    /**
    * @Route("/403", name="app_security_denied")
    */
    public function denied()
    {
        return $this->render('error/403.html.twig');
    }
}
