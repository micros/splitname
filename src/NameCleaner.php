<?php

declare(strict_types=1);

namespace Micros\Splitname;

use voku\helper\UTF8;


/**
 * Cleans the full name and affect the result
 *
 * There is a flag is the name changes
 */
final class NameCleaner
{
    public function clean(string $fullName): string
    {
        // Separate punctuation from names
        $needles = ['.', ',', ';', ':', '(', ')', '"', "_", "+", "="];
        // $replacements = [' . ', ' , ', ' ; ', ' : ', ' ( ', ' ) ', ' " ', " ' "];
        $fullName = str_replace($needles, '', $fullName);
        // Remove duplicate spaces, tabs and new lines
        $fullName = trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $fullName));
        // Remove non printable characters
        $fullName = preg_replace('/[\x00-\x1F\x7F]/u', '', $fullName);
        // Cleanup non UTF8 characters
        $fullName = UTF8::cleanup($fullName);

        return $fullName;
    }
}
