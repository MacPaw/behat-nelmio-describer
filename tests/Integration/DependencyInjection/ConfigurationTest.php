<?php

declare(strict_types=1);

namespace BehatNelmioDescriber\Tests\Integration\DependencyInjection;

use PHPUnit\Framework\TestCase;
use BehatNelmioDescriber\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testProcessConfiguration(): void
    {
        $expectedBundleConfig = [
            'behat_test_path' => '%kernel.project_dir%/tests/Behat/Features'
        ];

        $this->assertSame($expectedBundleConfig, $this->processConfiguration([
            'behat_test_path' => '%kernel.project_dir%/tests/Behat/Features'
        ]));
    }

    private function processConfiguration(array $values): array
    {
        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), ['behat_doctrine_fixtures' => $values]);
    }
}
