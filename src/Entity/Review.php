<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReviewRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'car_id_seq', allocationSize: 1, initialValue: 1)]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Car::class, inversedBy: 'reviews')]
    private $car;

    #[ORM\Column(type: 'integer')]
    #[Assert\Range(
        min: 1,
        max: 10,
        notInRangeMessage: "Star rating must be between {{ min }} and {{ max }}."
    )]
    #[Groups(["car_reviews"])]
    private int $starRating;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "Review text cannot be blank")]
    #[Groups(["car_reviews"])]
    private string $reviewText;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStarRating(): int
    {
        return $this->starRating;
    }

    public function setStarRating(int $starRating): self
    {
        $this->starRating = $starRating;

        return $this;
    }

    public function getReviewText(): string
    {
        return $this->reviewText;
    }

    public function setReviewText(string $reviewText): self
    {
        $this->reviewText = $reviewText;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }
}
