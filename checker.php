<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use App\Checker;
use App\Commands\Checker\Enums\InputOptions;
use App\Commands\Checker\Enums\ModeOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\SingleCommandApplication;

$command = (new SingleCommandApplication())->setName('Checker')->setVersion('1.0.0');

$command->addArgument(
    'input',
    InputArgument::REQUIRED,
    'The initial input to perform the check against.'
)->addArgument(
    'comparison',
    InputArgument::OPTIONAL,
    'The comparison string to check whether the initial input is an anagram of it.'
)->addOption(
    'input-type',
    null,
    InputArgument::OPTIONAL,
    'The type of input you will be providing ('.implode('|', InputOptions::all()).').',
    'string'
)->addOption(
    'mode',
    null,
    InputOption::VALUE_OPTIONAL,
    'The type of check you would like to perform ('.implode('|', ModeOptions::all()).').',
    'all'
);

$compareStrings = static function (
    string $stringToCheck,
    string|null $stringToCompare,
    string $mode,
    Checker $checker
): array {
    $messages = [];

    if (ModeOptions::PALINDROME === $mode || ModeOptions::ALL === $mode) {
        $messages[] = sprintf(
            'Is "%s" a palindrome: %s.',
            $stringToCheck,
            true === $checker->isPalindrome($stringToCheck) ? 'yes' : 'no'
        );
    }

    if (ModeOptions::ANAGRAM === $mode || ModeOptions::ALL === $mode) {
        if (null === $stringToCompare) {
            throw new Exception('The comparison argument is required for anagram checks (run the command with --help for more info).');
        }

        $messages[] = sprintf(
            'Is "%s" an anagram of "%s": %s.',
            $stringToCheck,
            $stringToCompare,
            true === $checker->isAnagram($stringToCheck, $stringToCompare) ? 'yes' : 'no'
        );
    }

    if (ModeOptions::PANGRAM === $mode || ModeOptions::ALL === $mode) {
        $messages[] = sprintf(
            'Is "%s" a pangram: %s.',
            $stringToCheck,
            true === $checker->isPangram($stringToCheck) ? 'yes' : 'no'
        );
    }

    return $messages;
};

$command->setCode(
    static function (InputInterface $input, OutputInterface $output) use ($compareStrings): void {
        $mode = $input->getOption('mode');

        if (false === array_search($mode, ModeOptions::all(), true)) {
            $output->writeln(
                sprintf(
                    'The mode "%s" is invalid (run the command with --help to see available choices).',
                    $mode
                )
            );

            exit(Command::INVALID);
        }

        $inputValue = $input->getArgument('input');
        $stringToCompare = $input->getArgument('comparison');
        $checker = new Checker();

        if (InputOptions::FILE === $input->getOption('input-type')) {
            $contents = @file_get_contents($inputValue);

            if (false === $contents) {
                throw new Exception(sprintf('The file "%s" provided is invalid.', $inputValue));
            }

            $messages = [];

            foreach (explode("\n", $contents) as $string) {
                if ('' === $string) {
                    continue;
                }

                $messages = array_merge(
                    $messages,
                    $compareStrings($string, $stringToCompare, $mode, $checker)
                );
            }
        } else {
            $messages = $compareStrings($inputValue, $stringToCompare, $mode, $checker);
        }

        // Output messages in order stated by the specification
        // This prevents any output in the instance of validation errors.
        foreach ($messages as $message) {
            $output->writeln($message);
        }

        exit(Command::SUCCESS);
    }
);

$command->run();
