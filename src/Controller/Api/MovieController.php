<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class MovieController extends AbstractController
{
    /**
     * @Route("/api/movies", name="app_api_movie_list", methods={"GET"})
     */
    public function list(MovieRepository $movieRepository): JsonResponse
    {
        $movies = $movieRepository->findAll();

        return $this->json($movies, Response::HTTP_OK, [], ['groups' => 'movies']);
    }

    /**
     * @Route("/api/movies/{id}", name="app_api_movie_find", methods={"GET"})
     */
    public function find($id, MovieRepository $movieRepository): JsonResponse
    {
        $movie = $movieRepository->find($id);

        return $this->json($movie, Response::HTTP_OK, [], ['groups' => 'movies']);
    }

    /**
     * @Route("/api/movies/random", name="app_api_movie_random", methods={"GET"})
     */
    public function movierandom(MovieRepository $movieRepository): JsonResponse
    {
        $movie = $movieRepository->findRandomMovie();

        return $this->json($movie, Response::HTTP_OK, [], ['groups' => 'movies']);
    }

    /**
     * @Route("/api/movies", name="app_api_movie_add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, MovieRepository $movieRepository): JsonResponse
    {
        $jsonContent = $request->getContent();

        try {
            $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        } catch (NotEncodableValueException $e) {
            return $this->json(["error" => "json invalide"], Response::HTTP_BAD_REQUEST);
        };

        $errors = $validator->validate($movie);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            };
            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // if (count($errors) > 0) {
        // $errorsString = (string) $errors;
        // return new JsonResponse($errorsString);
        // };

        $movieRepository->add($movie, true);

        // return new JsonResponse('The movie is created! Yes!');
        // return $this->json($movie,Response::HTTP_CREATED,[],['groups' => 'movies']);
        return $this->json($movie, Response::HTTP_CREATED, ["Location" => $this->generateUrl("app_api_movie_show", ["id" => $movie->getId()])], ["groups" => "movies"]);
    }

    /**
     * @Route("/api/genres/{id}/movies", name="app_api_movie_listByGenre", methods={"GET"})
     */
    public function listByGenre(Genre $genre): JsonResponse
    {
        //  récupérer les films
        $movies = $genre->getMovies();

        // on retour les films en json
        return $this->json($movies, Response::HTTP_OK, [], ["groups" => "movies"]);
    }

    /**
     * @Route("/api/movies/{id}", name="app_api_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): JsonResponse
    {
        return $this->json($movie, Response::HTTP_OK, [], ["groups" => "movies"]);
    }
}
