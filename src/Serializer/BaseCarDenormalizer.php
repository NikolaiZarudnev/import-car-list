<?php

namespace App\Serializer;

use App\DTO\BaseCar;
use App\DTO\Truck;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class BaseCarDenormalizer implements DenormalizerInterface
{
    private ObjectNormalizer $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $object = $this->objectNormalizer->denormalize($data, $type, $format, $context);

        if ($object instanceof Truck && !empty($data['body_whl'])) {
            $bodyWhl = [
                'width' => 0,
                'height' => 0,
                'length' => 0
            ];
            $parts = explode($data['body_whl'], 'x');

            if (isset($parts[0])) $bodyWhl['width'] = (int)$parts[0];
            if (isset($parts[1])) $bodyWhl['height'] = (int)$parts[1];
            if (isset($parts[2])) $bodyWhl['length'] = (int)$parts[2];

            $object->setBodyWidth(floatval($bodyWhl['width']));
            $object->setBodyHeight(floatval($bodyWhl['height']));
            $object->setBodyLength(floatval($bodyWhl['length']));
        }


        return $object;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type == BaseCar::class || is_subclass_of($type, BaseCar::class);
    }
}