<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use App\Checker;
use App\Commands\Checker\Enums\ModeOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

$checkStrings = static function (
    InputInterface $input,
    OutputInterface $output
): void {
    $mode = $input->getOption('mode');

    if (array_search($mode, ModeOptions::all(), true) === false) {
        $output->writeln(
            sprintf(
                'The mode "%s" is invalid (run the command with --help to see available choices).',
                $mode
            )
        );

        exit(Command::INVALID);
    }


    $stringToCheck = $input->getArgument('string');

    $checker = new Checker();

    $messages = [];

    if ($mode === ModeOptions::PALINDROME || $mode === ModeOptions::ALL) {
        if (true === $checker->isPalindrome($stringToCheck)) {
            $messages[] = sprintf(
                '"%s" is a palindrome.',
                $stringToCheck
            );
        } else {
            $messages[] = sprintf(
                '"%s" is not a palindrome.',
                $stringToCheck
            );
        }
    }

    if ($mode === ModeOptions::ANAGRAM || $mode === ModeOptions::ALL) {
        $stringToCompare = $input->getArgument('comparison');

        if ($stringToCompare === null) {
            $output->writeln(
                'The comparison argument is required for anagram checks (run the command with --help for more info).'
            );

            exit(Command::INVALID);
        }

        if (true === $checker->isAnagram($stringToCheck, $stringToCompare)) {
            $messages[] = sprintf(
                '"%s" is an anagram of "%s".',
                $stringToCheck,
                $stringToCompare
            );
        } else {
            $messages[] = sprintf(
                '"%s" is not an anagram of "%s".',
                $stringToCheck,
                $stringToCompare
            );
        }
    }

    if ($mode === ModeOptions::PANGRAM || $mode === ModeOptions::ALL) {
        if (true === $checker->isPangram($stringToCheck)) {
            $messages[] = sprintf(
                '"%s" is a pangram.',
                $stringToCheck
            );
        } else {
            $messages[] = sprintf(
                '"%s" is not a pangram.',
                $stringToCheck
            );
        }
    }

    // Output messages in order stated by the specification
    // This prevents any output in the instance of validation errors.
    foreach ($messages as $message) {
        $output->writeln($message);
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
    InputArgument::OPTIONAL,
    'The comparison string to check whether the initial string is an anagram of it.'
);

$command->addOption(
    'mode',
    null,
    InputOption::VALUE_OPTIONAL,
    'The type of check you would like to perform (' . implode('|', ModeOptions::all()) . ').',
    'all'
);

try {
    $command->setCode($checkStrings)->run();
} catch (Exception $exception) {
    // TODO:
}
