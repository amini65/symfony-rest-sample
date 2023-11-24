<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\CarFactory;
use App\Factory\ReviewFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class LatestReviewTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetLatestReviewsForCar(): void
    {
        // Create a car
        $car = CarFactory::createOne();

        // Create more than 5 reviews with varying ratings, some above and some below 6
        ReviewFactory::createMany(5, ['car' => $car, 'starRating' => 7]);
        ReviewFactory::createMany(2, ['car' => $car, 'starRating' => 4]); // These should not appear in the result

        // Request the latest reviews for the car
        $response = static::createClient()->request('GET', '/api/cars/'.$car->getId().'/latest_reviews');

        $this->assertResponseIsSuccessful();
        $this->assertJson($responseContent = $response->getContent());

        $responseData = json_decode($responseContent, true);
        $this->assertIsArray($responseData);

        // Check if the count is correct, assuming the endpoint returns exactly 5 reviews
        $this->assertCount(5, $responseData);

        // Assert that all reviews in the response have a rating greater than 6
        foreach ($responseData as $review) {
            $this->assertGreaterThan(6, $review['starRating']);
        }
    }
}
