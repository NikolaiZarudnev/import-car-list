<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Symfony\Component\Validator\Constraints as Assert;

#[DiscriminatorMap(typeProperty: 'car_type', mapping: [
    'car' => Car::class,
    'spec_machine' => SpecMachine::class,
    'truck' => Truck::class,
])]
abstract class BaseCar
{
    #[Assert\NotNull]
    protected ?string $carType;

    #[Assert\NotBlank]
    protected ?string $photoFileName;

    #[Assert\NotBlank]
    protected ?string $brand;
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    protected ?float $carrying;

    public function __construct(
        ?string $carType,
        ?string $photoFileName,
        ?string $brand,
        ?float $carrying
    ) {
        $this->carType = $carType;
        $this->photoFileName = $photoFileName;
        $this->brand = $brand;
        $this->carrying = $carrying;
    }

    public function getPhotoFileExt(): string
    {
        return '.' . pathinfo($this->photoFileName, PATHINFO_EXTENSION);
    }

    public function getCarType(): string
    {
        return $this->carType;
    }

    public function getPhotoFileName(): string
    {
        return $this->photoFileName;
    }

    public function setPhotoFileName(string $photoFileName): void
    {
        $this->photoFileName = $photoFileName;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function getCarrying(): float
    {
        return $this->carrying;
    }

    public function setCarrying(float $carrying): void
    {
        $this->carrying = $carrying;
    }
}