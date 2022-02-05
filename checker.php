<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

$command = new SingleCommandApplication();

$command->setName('Checker')->setVersion('1.0.0');

$command->setCode(
    static function (InputInterface $input, OutputInterface $output): void {
        // TODO:
    }
);

try {
    $command->run();
} catch (Exception $exception) {
    // TODO:
}
