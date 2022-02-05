<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use App\Checker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

$checkStrings = static function (
    InputInterface $input,
    OutputInterface $output
): void {
    $stringToCheck = $input->getArgument('string');
    $trimmedStringToCheck = str_replace(' ', '', $stringToCheck);

    if (false === ctype_alnum($trimmedStringToCheck)) {
        $output->writeln(
            'The string passed for checking must contain only alphanumeric characters and/or spaces.'
        );

        exit(Command::FAILURE);
    }

    $checker = new Checker();

    if (true === $checker->isPalindrome($stringToCheck)) {
        $output->writeln(
            sprintf(
                '"%s" is a palindrome.',
                $trimmedStringToCheck
            )
        );
    } else {
        $output->writeln(
            sprintf(
                '"%s" is not a palindrome.',
                $trimmedStringToCheck
            )
        );
    }

    $stringToCompare = $input->getArgument('comparison');
    $trimmedStringToCompare = str_replace(' ', '', $stringToCompare);

    // TODO: add comparison argument
    if (true === $checker->isAnagram($stringToCheck, $trimmedStringToCompare)) {
        $output->writeln(
            sprintf(
                '"%s" is an anagram of "%s".',
                $stringToCheck,
                $stringToCompare
            )
        );
    } else {
        $output->writeln(
            sprintf(
                '"%s" is not an anagram of "%s".',
                $stringToCheck,
                $stringToCompare
            )
        );
    }

    if (true === $checker->isPangram($trimmedStringToCheck)) {
        $output->writeln(
            sprintf(
                '"%s" is a pangram.',
                $stringToCheck
            )
        );
    } else {
        $output->writeln(
            sprintf(
                '"%s" is not a pangram.',
                $stringToCheck
            )
        );
    }

    exit(Command::SUCCESS);
};

$command = new SingleCommandApplication();

$command->setName('Checker')->setVersion('1.0.0');

$command->addArgument(
    'string',
    InputArgument::REQUIRED,
    'The initial string to perform the check against.'
)->addArgument(
    'comparison',
    InputArgument::REQUIRED,
    'The comparison string to check whether the initial string is an anagram of it.'
);

try {
    $command->setCode($checkStrings)->run();
} catch (Exception $exception) {
    // TODO:
}
