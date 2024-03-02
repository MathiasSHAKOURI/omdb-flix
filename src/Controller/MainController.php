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
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/genre/show/{id}", name="app_home_genre", methods={"GET"})
     */
    public function bygenre($id, MovieRepository $movieRepository, GenreRepository $genreRepository, Request $request, OmdbApiService $omdbApiService, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $genres = $genreRepository->findAllOrderByName();
        $session = $request->getSession();
        $moviesFavorites = $session->get('attributes-favoris');
        $genre = $genreRepository->find($id);
        $moviesByGenre = $genreRepository->findAllMovieByGenreOrderByName($id);

        $genreToGet = $moviesByGenre[0]->getMovies()->toArray();

        $pagination = $paginator->paginate(
            $genreToGet, /* query NOT result */
            $request->query->getInt('page', 1),5
        );

        return $this->render("main/home.html.twig",[
            'genres' => $genres,
            'movies_favorites' => $moviesFavorites,
            'pagination' => $pagination,
            'genreId' => $genre,
            'movies_by_genre' => $moviesByGenre
        ]);
    }
}