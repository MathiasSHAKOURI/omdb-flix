<?php

namespace App\Controller\Api;

use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="app_api_genre_list")
     */
    public function list(GenreRepository $genreRepository): JsonResponse
    {
        $genres = $genreRepository->findAll();

        return $this->json($genres,Response::HTTP_OK,[],['groups' => 'genres']);
    }

    // /**
    // * @Route("/api/genres/{id}/movies", name="app_api_genre_list_movies")
    // */
    // public function bygenre($id, GenreRepository $genreRepository): JsonResponse
    // {
    //     $genres = $genreRepository->find($id);

    //     return $this->json($genres,Response::HTTP_OK,[],['groups' => 'moviegenre']);
    // }
}
