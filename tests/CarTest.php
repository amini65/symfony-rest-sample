<?php

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Car;
use App\Factory\CarFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CarTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    public function testGetCollection(): void
    {
        CarFactory::createMany(5);

        $response = static::createClient()->request('GET', '/api/cars');

        $this->assertResponseIsSuccessful();

        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@id' => '/api/cars',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 5,
        ]);

        $this->assertCount(5, $response->toArray()['hydra:member']);

        $this->assertMatchesResourceCollectionJsonSchema(Car::class);
    }

    public function testCreateCar(): void
    {
        $response = static::createClient()->request('POST', '/api/cars', ['json' => [
            'brand' => 'Tesla',
            'model' => 'Model 3',
            'color' => 'Blue'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@type' => 'Car',
            'brand' => 'Tesla',
            'model' => 'Model 3',
            'color' => 'Blue',
            'reviews' => [],
        ]);
        $this->assertMatchesRegularExpression('~^/api/cars/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Car::class);
    }

    public function testCreateInvalidCar(): void
    {
        static::createClient()->request('POST', '/api/cars', ['json' => [
            'model' => '',
        ]]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testUpdateBook(): void
    {
        CarFactory::createOne(['model' => 'test-model']);

        $client = static::createClient();

        $iri = $this->findIriBy(Car::class, ['model' => 'test-model']);

        $client->request('PATCH', $iri, [
            'json' => [
                'brand' => 'BMW',
                'model' => 'X1',
                'color' => 'White'
            ],
            'headers' => [
                'Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteBook(): void
    {
        CarFactory::createOne(['model' => 'test-model']);

        $client = static::createClient();

        $iri = $this->findIriBy(Car::class, ['model' => 'test-model']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(Car::class)->findOneBy(['model' => 'test-model'])
        );
    }
}