<?php

declare(strict_types=1);

namespace Micros\Splitname;

use Micros\Splitname\Models\SplitTerm;

final class Classifier
{
    public function classify(array $values, string $pattern, array $rules): ?array
    {

        if (array_key_exists($pattern, $rules)) {
            $v = str_split($rules[$pattern]);

            $nm['first-name'] = null;
            $nm['middle-name'] = null;
            $nm['surname'] = null;
            $nm['other-surnames'] = null;
            $nm['gender'] = null;

            foreach ($v as $key => $value) {
                if ($value === '1') {
                    $nm['first-name'] = trim($nm['first-name'] . ' ' . $values[$key]['original']);
                    $nm['gender'] = $nm['gender'] ?? SplitTerm::where('term', $values[$key]['modified'])->where('type', 'N')->first()?->gender;
                }
                if ($value === '2') {
                    $nm['middle-name'] = trim($nm['middle-name'] . ' ' . $values[$key]['original']);
                    $nm['gender'] = $nm['gender'] ?? SplitTerm::where('term', $values[$key]['modified'])->where('type', 'N')->first()?->gender;
                }
                if ($value === '3') {
                    $nm['surname'] = trim($nm['surname'] . ' ' . $values[$key]['original']);
                }
                if ($value === '4') {
                    $nm['other-surnames'] = trim($nm['other-surnames'] . ' ' . $values[$key]['original']);
                }
            }
        }
        return $nm ?? null;
    }
}
