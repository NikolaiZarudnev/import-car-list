<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Truck extends BaseCar
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    private ?float $bodyWidth;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    private ?float $bodyHeight;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'float')]
    private ?float $bodyLength;

    public function __construct(
        ?string $photoFileName,
        ?string $brand,
        ?float  $carrying,
        ?float $bodyWidth = 0,
        ?float $bodyHeight = 0,
        ?float $bodyLength = 0,
    ) {
        parent::__construct(
            'truck',
            $photoFileName,
            $brand,
            $carrying,
        );

        $this->bodyWidth = $bodyWidth;
        $this->bodyHeight = $bodyHeight;
        $this->bodyLength = $bodyLength;
    }

    public function getBodyVolume(): float
    {
        return $this->bodyHeight * $this->bodyWidth * $this->bodyLength;
    }

    public function getBodyWidth(): float
    {
        return $this->bodyWidth;
    }

    public function setBodyWidth(float $bodyWidth): void
    {
        $this->bodyWidth = $bodyWidth;
    }

    public function getBodyHeight(): float
    {
        return $this->bodyHeight;
    }

    public function setBodyHeight(float $bodyHeight): void
    {
        $this->bodyHeight = $bodyHeight;
    }

    public function getBodyLength(): float
    {
        return $this->bodyLength;
    }

    public function setBodyLength(float $bodyLength): void
    {
        $this->bodyLength = $bodyLength;
    }
}