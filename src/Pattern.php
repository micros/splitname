<?php

declare(strict_types=1);

namespace Micros\Splitname;

use Micros\Splitname\Models\SplitRule;

final class Pattern
{
    public function get(array $values, array $sustitutions): array
    {
        $pattern = implode('', array_column($values, 'type'));

        $sustitution = str_replace('Z', 'X', $pattern);

        if (array_key_exists($sustitution, $sustitutions)) {
            $sustitution = $sustitutions[$sustitution];
        }

        if (str_contains($sustitution, 'X')) {
            $rules = SplitRule::whereRaw('LENGTH(rule) = ?', [strlen($sustitution)])->pluck('rule')->toArray();
            $closer = -1;
            $final = null;
            foreach ($rules as $rule) {
                // calculate the distance between the input word,
                // and the current word
                $lev = levenshtein($sustitution, $rule);

                // check for an exact match
                if ($lev == 0) {

                    // closest word is this one (exact match)
                    $final = $rule;
                    $closer = 0;

                    // break out of the loop; we've found an exact match
                    break;
                }

                // if this distance is less than the next found shortest
                // distance, OR if a next shortest word has not yet been found
                if ($lev <= $closer || $closer < 0) {
                    // set the closest match, and shortest distance
                    $final  = $rule;
                    $closer = $lev;
                }
            }
            $sustitution = $final ?? $sustitution;
        }
        return [$pattern, $sustitution];
    }
}
