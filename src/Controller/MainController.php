<?php

namespace App\Controller;

use App\Model\Movies;
use App\Service\OmdbApiService;
use Doctrine\ORM\EntityManager;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    // /**
    //  * @Route("/", name="app_home", methods={"GET"})
    //  */
    // public function home(MovieRepository $movieRepository, GenreRepository $genreRepository, Request $request, OmdbApiService $omdbApiService)
    // {
    //     // $movies = Movies::getMovies();
    //     $movies = $movieRepository->findAll();

    //     // foreach ($movies as $movie) {
    //     //         $movie->setPoster($omdbApiService->getPoster($movie));
    //     // };

    //     // $genres = $genreRepository->findAll();
    //     // $movies = $movieRepository->findAllOrderByTitleAsc();
    //     $genres = $genreRepository->findAllOrderByName();

    //     $session = $request->getSession();
    //     $moviesFavorites = $session->get('attributes-favoris');

    //     return $this->render("main/home.html.twig",[
    //         'movies' => $movies,
    //         'genres' => $genres,
    //         'movies_favorites' => $moviesFavorites
    //     ]);
    // }

        /**
     * @Route("/", name="app_home", methods={"GET"})
     */
    public function home(MovieRepository $movieRepository, GenreRepository $genreRepository, Request $request, OmdbApiService $omdbApiService, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $movies = $movieRepository->findAllOrderByTitleAsc();
        $genres = $genreRepository->findAllOrderByName();
        $session = $request->getSession();
        $moviesFavorites = $session->get('attributes-favoris');

        $pagination = $paginator->paginate(
            $movies, /* query NOT result */
            $request->query->getInt('page', 1),5
        );

        return $this->render("main/home.html.twig",[
            'movies' => $movies,
            'genres' => $genres,
            'movies_favorites' => $moviesFavorites,
            'pagination' => $pagination
        ]);
    }
}