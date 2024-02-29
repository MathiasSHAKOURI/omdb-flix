<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use DateTimeImmutable;
use App\Form\ReviewType;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\ArrayType;
use Doctrine\DBAL\Types\FloatType;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review/{id}", name="app_review")
     */
    public function add($id, MovieRepository $movieRepository, Request $request, ReviewRepository $reviewRepository): Response
    {
        $movie = $movieRepository->find($id);
        $review = new Review;

        $form = $this->createFormBuilder($review)
            ->add('content', TextareaType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 100])
                ]
            ])
            ->add('email', EmailType::class,[
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('username', TextType::class,[
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('rating', ChoiceType::class,[
                'choices' => [
                "Excellent" => 5,
                "Très bon" => 4,
                "Bon" => 3,
                "Peut mieux faire" => 2,
                "A éviter" => 1
                ],
            ])
            ->add('reaction', ChoiceType::class, [
                'choices' => [
                    "Rire" => 'Rire',
                    "Pleurer" => 'Pleurer',
                    "Réfléchir" => 'Réfléchir',
                    "Dormir" => 'Dormir',
                    "Rêver" => 'Rêver'
                ],
                'mapped' => false,
                'expanded' => true,
                'multiple' => true
            ])
            ->add('watchedAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var array $testData
             */
            $testData = $request->request->get('form')['reaction'];
            $review->setReaction($testData);
            $review->setMovie($movie);
            // correction pour rating listener
            // $movie->addReview($review);
            $review->setPublishedAt(new DateTimeImmutable());
            $movie->addReview($review);

            $reviewRepository->add($review, true);

            // $reviews = $movie->getReviews();
            // $rating = 0;
            // $numberOfFilm = 0;
    
            // foreach ($reviews as $key => $value) {
            //     $rating = $rating + $reviews[$key]->getRating();
            //     if ($reviews[$key]->getRating() > 0)
            //     {
            //         $numberOfFilm++;
            //     };
            // };
    
            // if ($numberOfFilm != 0) {
            //     $filmRating = $rating / $numberOfFilm;
            //     $movie->setRating($filmRating);
            //     $movieRepository->add($movie, true);
            // };

            return $this->redirectToRoute('app_show', [
                'id' => $id,
                'slug' => $movie->getSlug()
            ]);
        };    

        return $this->render('review/form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
    * Ajout d'une critique sur un film
    * @Route("/movie/{id}/review/add", name="app_movie_review_add")
    */
    public function addcorrection(Movie $movie, Request $request, ReviewRepository $reviewRepository, MovieRepository $movieRepository): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $review->setMovie($movie);
            $review->setPublishedAt(new DateTimeImmutable());
            $reviewRepository->add($review, true);

            $reviews = $movie->getReviews();
            $rating = 0;
            $numberOfFilm = 0;
    
            foreach ($reviews as $key => $value) {
                $rating = $rating + $reviews[$key]->getRating();
                if ($reviews[$key]->getRating() > 0)
                {
                    $numberOfFilm++;
                };
            };
    
            if ($numberOfFilm != 0) {
                $filmRating = $rating / $numberOfFilm;
                $movie->setRating($filmRating);
                $movieRepository->add($movie, true);
            };

            return $this->redirectToRoute('app_show', [
                'id' => $movie->getId()
            ]);
        };  

        return $this->renderForm('review/add.html.twig',[
            'movie' => $movie,
            'form' => $form
        ]);
    }
}
