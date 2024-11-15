<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SpecMachine extends BaseCar
{
    #[Assert\NotBlank]
    private ?string $extra;

    public function __construct(
        ?string $photoFileName,
        ?string $brand,
        ?float  $carrying,
        ?string $extra
    ) {
        parent::__construct(
            'specMachine',
            $photoFileName,
            $brand,
            $carrying
        );

        $this->extra = $extra;
    }

    public function getExtra(): string
    {
        return $this->extra;
    }

    public function setExtra(string $extra): void
    {
        $this->extra = $extra;
    }
}