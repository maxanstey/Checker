<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use App\Checker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

$command = new SingleCommandApplication();

$command->setName('Checker')->setVersion('1.0.0');

$command->addArgument(
    'string',
    InputArgument::REQUIRED,
    'The string to perform the check against.'
);

$command->setCode(
    static function (InputInterface $input, OutputInterface $output): void {
        $stringToCheck = $input->getArgument('string');

        if (false === ctype_alnum($stringToCheck)) {
            $output->writeln('The string passed for checking must contain only alphanumeric characters.');

            exit(Command::FAILURE);
        }

        $checker = new Checker();

        if (true === $checker->isPalindrome($stringToCheck)) {
            $output->writeln("$stringToCheck is a palindrome.");
        }

        // TODO: add comparison argument
        if (true === $checker->isAnagram($stringToCheck, 'comparison')) {
            $output->writeln("$stringToCheck is an anagram.");
        }

        if (true === $checker->isPangram($stringToCheck)) {
            $output->writeln("$stringToCheck is a pangram.");
        }

        exit(Command::SUCCESS);
    }
);

try {
    $command->run();
} catch (Exception $exception) {
    // TODO:
}
