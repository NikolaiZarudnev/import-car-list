<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Car extends BaseCar
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $passengerSeatsCount;

    public function __construct(
        ?string $photoFileName,
        ?string $brand,
        ?float  $carrying,
        ?int    $passengerSeatsCount,
    ) {
        parent::__construct(
            'car',
            $photoFileName,
            $brand,
            $carrying,
        );

        $this->passengerSeatsCount = $passengerSeatsCount;
    }

    public function getPassengerSeatsCount(): int
    {
        return $this->passengerSeatsCount;
    }

    public function setPassengerSeatsCount(int $passengerSeatsCount): void
    {
        $this->passengerSeatsCount = $passengerSeatsCount;
    }
}