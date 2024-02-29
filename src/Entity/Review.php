<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"movies"})
     */
    private $content;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"movies"})
     */
    private $rating;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"movies"})
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"movies"})
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="reviews")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $movie;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"movies"})
     */
    private $reaction = [];

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"movies"})
     */
    private $watchedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getReaction(): ?array
    {
        return $this->reaction;
    }

    public function setReaction(array $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
    }

    public function getWatchedAt(): ?\DateTimeImmutable
    {
        return $this->watchedAt;
    }

    public function setWatchedAt(\DateTimeImmutable $watchedAt): self
    {
        $this->watchedAt = $watchedAt;

        return $this;
    }
}
