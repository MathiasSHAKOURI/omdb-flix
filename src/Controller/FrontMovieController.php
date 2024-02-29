<?php

namespace App\Controller;

use App\Model\Movies;
use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Service\OmdbApiService;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontMovieController extends AbstractController
{
    /**
     * @Route("/movie", name="app_movie", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository, GenreRepository $genreRepository): Response
    {
        $movies = $movieRepository->findAll();
        $genres = $genreRepository->findAllOrderByName();

        return $this->render('front_movie/list.html.twig',[
            'movies' => $movies,
            'genres' => $genres,
        ]);
    }

    /**
    * @Route("/show/{id}/{slug}", name="app_show", methods={"GET"})
    */
    public function show($id, MovieRepository $movieRepository, CastingRepository $castingRepository, OmdbApiService $omdbApiService){
        // $movie = Movies::getMovieById($id);
        $movie = $movieRepository->find($id);
        // $movie->setPoster($omdbApiService->getPoster($movie));
        
        if ($movie === null) {
            // throw $this->createNotFoundException('Film ou série non trouvé');
            return $this->render('error/error.html.twig');
        };

        $castings = $castingRepository->findAllForMovie($movie);

        return $this->render('front_movie/show.html.twig',[
            'movie' => $movie,
            'castings' => $castings
        ]);
    }

    /**
    * @Route("/search", name="app_movie_search", methods={"GET"})
    */
    public function search(MovieRepository $movieRepository, GenreRepository $genreRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $search = $request->query->get("search");
        $movies = $movieRepository->findSearch($search);
        $genres = $genreRepository->findAllOrderByName();

        $pagination = $paginator->paginate(
            $movies, /* query NOT result */
            $request->query->getInt('page', 1),5
        );

        return $this->render('front_movie/list.html.twig',[
            'movies' => $movies,
            'genres' => $genres,
            'pagination' => $pagination
        ]);
    }
}
