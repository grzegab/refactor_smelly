<?php

declare(strict_types=1);

namespace App\Tests\Calculator\Command;

use App\Calculator\App\Service;
use App\Calculator\Command\RateCalculation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RateCalculationTest extends KernelTestCase
{
    /**
     * @dataProvider basicRatesProvider
     */
    public function testBasicRates(array $testRates): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $calculationService = $this->createMock(Service::class);
        $calculationService->expects(self::once())->method('calculateRates')->willReturn($testRates);

        $application = new Application($kernel);
        $application->add(new RateCalculation($calculationService));

        $command = $application->find('app:rate-calc');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'filename' => 'aaaa.txt']);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString('Rate calculations', $output);
        foreach ($testRates as $testValue) {
            self::assertStringContainsString((string)$testValue, $output);
        }
    }

    public function testEmpty(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $calculationService = $this->createMock(Service::class);
        $calculationService->expects(self::once())->method('calculateRates')->willReturn([]);

        $application = new Application($kernel);
        $application->add(new RateCalculation($calculationService));

        $command = $application->find('app:rate-calc');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'filename' => 'aaaa.txt']);

        $output = $commandTester->getDisplay();
        self::assertSame("\nRate calculations\n=================\n\n", $output);
    }

    public function testException(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();

        $calculationService = $this->createMock(Service::class);
        $exception = new \RuntimeException('Test error');
        $calculationService->expects(self::once())->method('calculateRates')->willThrowException($exception);

        $application = new Application($kernel);
        $application->add(new RateCalculation($calculationService));

        $command = $application->find('app:rate-calc');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'filename' => 'aaaa.txt']);

        $output = $commandTester->getDisplay();
        self::assertStringContainsString("Error:", $output);
    }

    private function basicRatesProvider(): array
    {
        return [
            [[1, 0.5, 4]],
            [[1, 1, 1, 1]],
            [[-219219, 32784238, 0, -0, 12893]],
            [[-1, 1, -1, 1]],
            [['1', '34435', 34.34435, 0.011111111, 0.0]],
            [[null, false, 0, -0, 2137812798371237812]]
        ];
    }
}