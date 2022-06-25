<?php

declare(strict_types=1);

namespace App\Calculator\Command;

use App\Calculator\App\Service;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:rate-calc')]
final class RateCalculation extends Command
{
    protected static $defaultName = 'app:rate-calc';
    protected static $defaultDescription = 'Runs calculator';

    public function __construct(private readonly Service $service)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'Filename for calculations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $rates = $this->service->calculateRates($input->getArgument('filename'));

            $output->writeln(["\nRate calculations", '=================']);
            foreach ($rates as $rate) {
                $output->write("$rate\n");
            }
            $output->write("\n");

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->write("\n");
            $output->write(sprintf("Error: %s\n", $e->getMessage()));
            $output->write("\n");

            return Command::FAILURE;
        }
    }
}
