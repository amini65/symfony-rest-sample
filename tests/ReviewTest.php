<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Review;
use App\Factory\CarFactory;
use App\Factory\ReviewFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReviewTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testCreateReview(): void
    {
        $car = CarFactory::createOne();

        $response = static::createClient()->request('POST', '/api/reviews', ['json' => [
            'starRating' => 5,
            'reviewText' => 'Great car!',
            'car' => '/api/cars/'.$car->getId(),
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Review',
            '@type' => 'Review',
            'starRating' => 5,
            'reviewText' => 'Great car!',
            'car' => '/api/cars/'.$car->getId(),
        ]);
        $this->assertMatchesResourceItemJsonSchema(Review::class);
    }

    public function testCreateInvalidReview(): void
    {
        static::createClient()->request('POST', '/api/reviews', ['json' => [
            'starRating' => 11, // Invalid rating
            'reviewText' => '',
        ]]);

        $this->assertResponseStatusCodeSame(422); // Assuming validation is set up to return a 422 response
    }

    public function testUpdateReview(): void
    {
        $review = ReviewFactory::createOne();

        $client = static::createClient();
        $iri = $this->findIriBy(Review::class, ['id' => $review->getId()]);

        $client->request('PUT', $iri, [
            'json' => [
                'starRating' => 4,
                'reviewText' => 'Updated review text.'
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'starRating' => 4,
            'reviewText' => 'Updated review text.'
        ]);
    }

    public function testDeleteReview(): void
    {
        $review = ReviewFactory::createOne(['reviewText' => 'test-review']);

        $client = static::createClient();
        $iri = $this->findIriBy(Review::class, ['reviewText' => 'test-review']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Review::class)->findOneBy(['reviewText' => 'test-review'])
        );
    }

}
