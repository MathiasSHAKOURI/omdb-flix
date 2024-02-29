<?php
namespace App\EventListener;

use App\Entity\Review;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;

class MovieRatingListener{

    private $movieRepository;
    private $entityManagerInterface;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function movieRating(Review $review)
    {
        $rating = $this->movieRepository->averageRating($review->getMovie()->getId());
        $movieRating = $review->getMovie()->setRating($rating);
        $this->entityManagerInterface->flush($movieRating);
    }
}