<?php

namespace App\Commands\Checker\Enums;

use App\Enums\Enum;

class ModeOptions extends Enum
{
    public const PALINDROME = 'palindrome';
    public const ANAGRAM = 'anagram';
    public const PANGRAM = 'pangram';
    public const ALL = 'all';
}
