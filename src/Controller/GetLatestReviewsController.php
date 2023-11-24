<?php

namespace App\Controller;

use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetLatestReviewsController extends AbstractController
{
    #[Route('/api/cars/{id}/latest_reviews', methods: ['GET'])]
    public function getLatestReviews(ReviewRepository $reviewRepository, SerializerInterface $serializer, $id): Response
    {
        $reviews = $reviewRepository->findLatestHighRatedReviewsForCar($id);

        $json = $serializer->serialize($reviews, 'json', ['groups' => ['car_reviews']]);


        return new Response($json, 200, ['Content-Type' => 'application/json']);
    }
}
