<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DemoSessionController extends AbstractController
{
    /**
     * @Route("/demo/session", name="app_demo_session")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        // var_dump($session);
        $userName = $session->get('user_name', 'inconnu');
        // var_dump($userName);

        return $this->render('demo_session/index.html.twig', [
            'user_name' => $userName,
        ]);
    }

    /**
     * Undocumented function
     *  @Route("/demo/session/{name}", name="app_demo_session_add")
     */
    public function add(Request $request, $name)
    {
        $session = $request->getSession();
        $session->set('user_name', $name);
        return $this->redirectToRoute('app_demo_session');
    }
}
