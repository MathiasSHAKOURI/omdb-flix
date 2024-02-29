<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use App\Service\FavoriteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoritesController extends AbstractController
{
    /**
    * @Route("/favorites", name="app_favorites", methods={"GET"})
    */
    public function list(Request $request)
    {
        $session = $request->getSession();
        $movieFavorites = $session->get('attributes-favoris');

        return $this->render("main/favorites.html.twig",[
            'movies' => $movieFavorites
        ]);
    }

    /**
    * @Route("/favorites/add/{id}", name="app_favorites_add", methods={"GET"})
    */
    public function add($id, Request $request, MovieRepository $movieRepository, FavoriteService $favoriteService)
    {
        // $movieToAdd = $movieRepository->find($id);
        // $session = $request->getSession();
        // $moviesFavorites = $session->get('attributes-favoris');
        // $moviesFavorites[$id]= $movieToAdd;
        // $session->set('attributes-favoris', $moviesFavorites);

        // $movieToAdd = $favoriteService->add($id, $request);

        // $this->addFlash(
        //     'success',
        //     $movieToAdd->getTitle() .' a été ajouté à la liste de favoris.'
        // );
        $favoriteService->toggle($id);

        return $this->redirectToRoute('app_favorites');
    }

    /**
    * @Route("/favorites/empty", name="app_favorites_empty", methods={"GET"})
    */
    public function empty(Request $request, FavoriteService $favoriteService)
    {
        // $session = $request->getSession();
        // $session->set('attributes-favoris', []);

        $favoriteService->empty();

        return $this->redirectToRoute('app_favorites');
    }

    /**
    * @Route("/favorites/delete/{id}", name="app_favorites_delete", methods={"GET"})
    */
    public function delete($id, Request $request, FavoriteService $favoriteService)
    {
        // $session = $request->getSession();
        // $moviesFavorites = $session->get('attributes-favoris');
        // $movieTitle = $moviesFavorites[$id]->getTitle();
        // unset($moviesFavorites[$id]);
        // $session->set('attributes-favoris', $moviesFavorites);

        // $movieTitle = $favoriteService->delete($id, $request);

        // $this->addFlash(
        //     'danger',
        //     $movieTitle .' a été retiré de la liste de favoris.'
        // );

        $favoriteService->toggle($id);

        return $this->redirectToRoute('app_favorites');
    }

    /**
    * @Route("/favorites/{id}", name="app_favorites_toggle", methods={"GET"})
    */
    public function toggle($id,FavoriteService $favoriteService)
    {
        $favoriteService->toggle($id);

        return $this->redirectToRoute('app_favorites');
    }
}