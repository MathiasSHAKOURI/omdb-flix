<?php

namespace App\Controller\Back;

use DateTimeZone;
use App\Entity\Genre;
use App\Entity\Movie;
use DateTimeImmutable;
use App\Form\MovieType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Service\MyMailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/back/movie")
 * @isGranted("BACK_OFFICE_ACCESS")
 */
class BackMovieController extends AbstractController
{
    /**
     * @Route("/index", name="app_admin_movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        // $now = new DateTimeImmutable("now", new DateTimeZone("Europe/Paris"));
        // $hour = $now->format('H');

        // if ($hour > 13){
        //     throw $this->createAccessDeniedException("Rentre chez toi !");
        // };
        
        return $this->render('back/movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MovieRepository $movieRepository, ManagerRegistry $doctrine, GenreRepository $genreRepository, MyMailerService $mailer): Response
    {
        $movie = new Movie();
        $genres = $genreRepository->findAll();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            // $email = (new TemplatedEmail())
            // ->from('ivarieth@gmail.com')
            // ->to('mathias.shakouri@gmail.com')
            // //->cc('cc@example.com')
            // //->replyTo('fabien@example.com')
            // //->priority(Email::PRIORITY_HIGH)
            // ->subject($movie->getTitle())
            // ->text('Un film a été ajouté !')
            // ->htmlTemplate("email/movie_created.html.twig")
            // // ->html('<h1>Le film '.$movie->getTitle().' a été crée.')
            // ->context([
            //     "movie" => $movie
            // ]);

            // $mailer->send($email);

            $mailer->alertToAdmin($movie->getTitle(),"movie_created.html.twig",["movie" => $movie]);

            return $this->redirectToRoute('app_admin_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('back/movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movieRepository->add($movie, true);

            $this->addFlash(
                'notice',
                'Changement effectué avec succès !'
            );

            return $this->redirectToRoute('app_admin_movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, MovieRepository $movieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            $movieRepository->remove($movie, true);
        }

        return $this->redirectToRoute('app_admin_movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
