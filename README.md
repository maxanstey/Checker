# Checker
A Symfony application that checks whether strings are a palindrome, anagram, or pangram.

# Getting started

Simply clone the repository and run composer install.

# Example usage

You can run the below commands to test it out:

```
php checker --help

php checker.php testtest --mode=palindrome
php checker.php testtset --mode=palindrome

php checker.php testtest --mode=pangram
php checker.php abcdefghijklmnopqrstuvwxyz --mode=pangram
php checker.php abcdefghijklmnopqrstuvwxyzzzz --mode=pangram

php checker.php testtest stetstet --mode=anagram
php checker.php testtest stetstet123 --mode=anagram

php checker.php testtset stetstet123
php checker.php testtset stetstet123
php checker.php abcdefghijklmnopqrstuvwxyz stetstet123
php checker.php testtest stetstet123

# Create a file in the root of the repository named FILENAME and run:

php checker.php --input-type=file FILENAME stetstet123
php checker.php --input-type=file FILENAME stetstet123
php checker.php --input-type=file FILENAME stetstet123
php checker.php --input-type=file FILENAME stetstet123
```
