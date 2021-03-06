<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use App\Checker;
use App\Commands\Checker\Enums\InputOption as InputOptionEnum;
use App\Commands\Checker\Enums\ModeOption;
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
    'The type of input you will be providing ('.implode('|', InputOptionEnum::all()).').',
    InputOptionEnum::STRING
)->addOption(
    'mode',
    null,
    InputOption::VALUE_OPTIONAL,
    'The type of check you would like to perform ('.implode('|', ModeOption::all()).').',
    ModeOption::ALL
);

$compareStrings = static function (
    string $stringToCheck,
    string|null $stringToCompare,
    string $mode,
    Checker $checker
): array {
    $messages = [];

    if (ModeOption::PALINDROME === $mode || ModeOption::ALL === $mode) {
        $messages[] = sprintf(
            'Is "%s" a palindrome: %s.',
            $stringToCheck,
            true === $checker->isPalindrome($stringToCheck) ? 'yes' : 'no'
        );
    }

    if (ModeOption::ANAGRAM === $mode || ModeOption::ALL === $mode) {
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

    if (ModeOption::PANGRAM === $mode || ModeOption::ALL === $mode) {
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

        if (false === ModeOption::isValid($mode)) {
            throw new Exception(sprintf('The mode "%s" is invalid (run the command with --help to see available choices).', $mode));
        }

        $inputValue = $input->getArgument('input');
        $stringToCompare = $input->getArgument('comparison');
        $checker = new Checker();

        if (InputOptionEnum::FILE === $input->getOption('input-type')) {
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

        // Output messages in order stated by the specification.
        // This prevents any output in the instance of validation errors.
        foreach ($messages as $message) {
            $output->writeln($message);
        }

        exit(Command::SUCCESS);
    }
);

$command->run();
