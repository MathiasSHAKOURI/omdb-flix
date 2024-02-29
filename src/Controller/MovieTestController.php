<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Season;
use App\Repository\MovieRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieTestController extends AbstractController
{
    /**
     * @Route("/movie/test", name="app_movie_test")
     */
    public function index(): Response
    {
        return $this->render('movie_test/index.html.twig', [
            'controller_name' => 'MovieTestController',
        ]);
    }

    /**
     * @Route("/movie/test/add", name="app_movie_test_add")
     */
    public function add(ManagerRegistry $doctrine, MovieRepository $movieRepository)
    {
        $entityManager = $doctrine->getManager();

        $movie = new Movie();

        $movie->setTitle("Walking Dead");
        $movie->setType("Série");
        $movie->setReleaseDate(new DateTime("1995-05-14"));
        $movie->setDuration(50);
        $movie->setPoster("https://www.ecranlarge.com/uploads/image/001/457/the-walking-dead-photo-1457455.jpg");
        $movie->setRating(2.1);
        $movie->setSummary("The Walking DeadNote 1 est une série télévisée d'horreur / dramatique américaine, tout d'abord développée par Frank Darabont d'après la bande dessinée du même nom de Robert Kirkman, Tony Moore et Charlie Adlard, diffusée aux États-Unis du 31 octobre 20101 au 20 novembre 2022 sur AMC.");
        $movie->setSynopsis("The Walking DeadNote 1 est une série télévisée d'horreur / dramatique américaine, tout d'abord développée par Frank Darabont d'après la bande dessinée du même nom de Robert Kirkman, Tony Moore et Charlie Adlard, diffusée aux États-Unis du 31 octobre 20101 au 20 novembre 2022 sur AMC.");
        $movieRepository->add($movie, true);

        $s1 = new Season;
        $s1->setNumber(1)
        ->setEpisodesNumber(6)
        ->setMovie($movie);

        $s2 = new Season;
        $s2->setNumber(2)
        ->setEpisodesNumber(6)
        ->setMovie($movie);

        $horreur = new Genre;
        $horreur->setName("Horreur");
        $movie->addGenre($horreur);

        $aventure = new Genre;
        $aventure->setName("Aventure");
        $movie->addGenre($aventure);

        // $entityManager->persist($movie);
        $entityManager->persist($s1);
        $entityManager->persist($s2);
        $entityManager->persist($horreur);
        $entityManager->persist($aventure);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/movie/test/edit/{id}", name="app_movie_test_edit")
     */
    public function edit($id, MovieRepository $movieRepository, ManagerRegistry $doctrine)
    {
        $movie = $movieRepository->find($id);
        $movie->setPoster("https://m.media-amazon.com/images/I/81eY+Q2pOGL.jpg");

        $entityManager = $doctrine->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}