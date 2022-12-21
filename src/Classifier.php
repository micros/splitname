<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Micros\Names\App\Models\Term;

final class Classifier
{
    public function classify(array $values, string $pattern, array $rules): ?array
    {

        if (array_key_exists($pattern, $rules)) {
            $v = str_split($rules[$pattern]);

            $nm['first-name'] = null;
            $nm['other-names'] = null;
            $nm['last-name'] = null;
            $nm['other-last-names'] = null;
            $nm['gender'] = null;

            foreach ($v as $key => $value) {
                if ($value === '1') {
                    $nm['first-name'] = trim($nm['first-name'] . ' ' . $values[$key]['original']);
                    $nm['gender'] = $nm['gender'] ?? Term::where('term', $values[$key]['modified'])->where('type', 'N')->first()?->gender;
                }
                if ($value === '2') {
                    $nm['other-names'] = trim($nm['other-names'] . ' ' . $values[$key]['original']);
                    $nm['gender'] = $nm['gender'] ?? Term::where('term', $values[$key]['modified'])->where('type', 'N')->first()?->gender;
                }
                if ($value === '3') {
                    $nm['last-name'] = trim($nm['last-name'] . ' ' . $values[$key]['original']);
                }
                if ($value === '4') {
                    $nm['other-last-names'] = trim($nm['other-last-names'] . ' ' . $values[$key]['original']);
                }
            }
        }
        return $nm ?? null;
    }
}
