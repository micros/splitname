<?php

declare(strict_types=1);

namespace Micros\Splitname;

use voku\helper\ASCII;

/**
 * Cleans the full name and affect the result
 *
 * There is a flag is the name changes
 */
final class PartCleaner
{
    public function clean(string $value): string
    {
        $value = preg_replace("/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ\-\' ]/", '', $value);
        $value = ASCII::to_transliterate($value);
        $value = mb_strtolower($value, 'UTF-8');
        return $value;
    }
}
