<?php

namespace App\Service;

use App\DTO\BaseCar;
use App\Serializer\BaseCarDenormalizer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseCarConverterService
{
    public function __construct(
        private SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly LoggerInterface $logger
    )
    {
        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader());

        $discriminator = new ClassDiscriminatorFromClassMetadata($classMetadataFactory);
        $objectNormalizer = new ObjectNormalizer($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter(), null, null, $discriminator);

        $this->serializer = new Serializer(
            [
                new ArrayDenormalizer(),
                new BaseCarDenormalizer($objectNormalizer),
            ],
            [
                new CsvEncoder([CsvEncoder::DELIMITER_KEY => ';']),
            ]
        );
    }

    /**
     * @param array $rows
     * @return BaseCar[]
     */
    public function getCarList(array $rows): array
    {
        $cars = [];

        $header = array_shift($rows);
        foreach ($rows as $row) {
            $carData = $this->serializer->decode($header . "\n" . $row, 'csv', [CsvEncoder::AS_COLLECTION_KEY => false]);
            try {
                $car = $this->serializer->denormalize($carData, BaseCar::class);
            } catch (\Exception $exception) {
                $this->logger->error($exception->getMessage());
                continue;
            }

            $errors = $this->validator->validate($car);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                $this->logger->error(implode('; ', $errorMessages));
            }

            $cars[] = $car;
        }

        return $cars;
    }
}