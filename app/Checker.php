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

        sort($lettersFromWord);
        sort($lettersFromComparison);

        return implode('', $lettersFromWord) === implode('', $lettersFromComparison);
    }

    /**
     * A Pangram for a given alphabet is a sentence using every letter of the
     * alphabet at least once.
     */
    public function isPangram(string $phrase): bool
    {
        $capitalisedAndUniquePhraseLetters = array_unique(str_split($this->formatStringForChecking($phrase)));

        sort($capitalisedAndUniquePhraseLetters);

        return str_contains(
            implode('', $capitalisedAndUniquePhraseLetters),
            implode('', range('A', 'Z'))
        );
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
