<?php

namespace App;

/**
 * Pangrams, anagrams and palindromes.
 *
 * Implement each of the functions of the Checker class.
 * Aim to spend about 10 minutes on each function.
 */
class Checker
{
    /**
     * A palindrome is a word, phrase, number, or other sequence of characters
     * which reads the same backward or forward.
     */
    public function isPalindrome(string $word): bool
    {
        return $this->formatStringForChecking($word) === strrev($this->formatStringForChecking($word));
    }

    /**
     * An anagram is the result of rearranging the letters of a word or phrase
     * to produce a new word or phrase, using all the original letters
     * exactly once.
     */
    public function isAnagram(string $word, string $comparison): bool
    {
        $lettersFromWord = str_split($this->formatStringForChecking($word));
        $lettersFromComparison = str_split($this->formatStringForChecking($comparison));

        foreach ($lettersFromWord as $index => $letter) {
            $comparisonLetterIndex = array_search($letter, $lettersFromComparison, true);

            if (false === $comparisonLetterIndex) {
                continue;
            }

            unset(
                $lettersFromWord[$index],
                $lettersFromComparison[$comparisonLetterIndex]
            );
        }

        return 0 === count($lettersFromWord) && 0 === count($lettersFromComparison);
    }

    /**
     * A Pangram for a given alphabet is a sentence using every letter of the
     * alphabet at least once.
     */
    public function isPangram(string $phrase): bool
    {
        $capitalisedAndUniquePhraseLetters = array_map(
            'strtoupper',
            array_unique(str_split($this->formatStringForChecking($phrase)))
        );

        $alphasNotPresentInPhrase = array_filter(
            range('A', 'Z'),
            static function (string $letter) use ($capitalisedAndUniquePhraseLetters): bool {
                return false === array_search($letter, $capitalisedAndUniquePhraseLetters, true);
            }
        );

        return 0 === count($alphasNotPresentInPhrase);
    }

    /**
     * Takes a string and returns it without non-alphanumeric characters
     * and in uppercase.
     */
    private function formatStringForChecking(string $string): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $string));
    }
}
