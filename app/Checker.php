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
        return $word === strrev($word);
    }

    /**
     * An anagram is the result of rearranging the letters of a word or phrase
     * to produce a new word or phrase, using all the original letters
     * exactly once.
     */
    public function isAnagram(string $word, string $comparison): bool
    {
        $lettersFromWord = str_split($word);
        $lettersFromComparison = str_split($comparison);

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
        return true;
    }
}
