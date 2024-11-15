<?php

namespace App\Command;

use App\Service\BaseCarConverterService;
use App\Service\CsvReaderService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:get-car-list'
)]
class GetCarListCommand extends Command
{
    public function __construct(
        private readonly CsvReaderService   $csvReaderService,
        private readonly BaseCarConverterService $baseCarConverterService,
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filename = 'public/uploads/Исходные_данные_для_задания_с_машинами.csv';

        try {
            $rows = $this->csvReaderService->getRows($filename);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return Command::FAILURE;
        }

        $cars = $this->baseCarConverterService->getCarList($rows);

        //For view
        $jsonData = $this->serializer->serialize($cars, 'json');
        $io->success($jsonData);

        return Command::SUCCESS;
    }
}
