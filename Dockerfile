FROM php:8.1-fpm-alpine

RUN apk update

RUN apk add postgresql postgresql-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /** @ORM\Column(type="string") */
    private string $brand;

    /** @ORM\Column(type="string") */
    private string $model;

    /** @ORM\Column(type="string") */
    private string $color;
}
