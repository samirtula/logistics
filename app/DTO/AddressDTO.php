<?php

declare(strict_types=1);

namespace App\DTO;

class AddressDTO
{
    public function __construct(
        private readonly string $country,
        private readonly string $zip,
        private readonly string $city
    ) {
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getFormated(): string
    {
        return sprintf('%s,%s,%s', $this->city, $this->zip, $this->country);
    }
}
