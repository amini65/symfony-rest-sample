<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ApiResource]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'car_id_seq', allocationSize: 1, initialValue: 1)]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Brand name cannot be blank")]
    private string $brand;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Model cannot be blank")]
    private string $model;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Color cannot be blank")]
    private string $color;

    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'car')]
    #[Groups(["car_reviews"])]
    private $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }
}
