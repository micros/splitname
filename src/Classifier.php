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

            $nm['PN'] = null;
            $nm['SN'] = null;
            $nm['PA'] = null;
            $nm['SA'] = null;
            $nm['gender'] = null;

            foreach ($v as $key => $value) {
                if ($value === '1') {
                    $nm['PN'] = trim($nm['PN'] . ' ' . $values[$key]['original']);
                    $nm['gender'] = $nm['gender'] ?? Term::where('term', $values[$key]['modified'])->where('type', 'N')->first()?->gender;
                }
                if ($value === '2') {
                    $nm['SN'] = trim($nm['SN'] . ' ' . $values[$key]['original']);
                    $nm['gender'] = $nm['gender'] ?? Term::where('term', $values[$key]['modified'])->where('type', 'N')->first()?->gender;
                }
                if ($value === '3') {
                    $nm['PA'] = trim($nm['PA'] . ' ' . $values[$key]['original']);
                }
                if ($value === '4') {
                    $nm['SA'] = trim($nm['SA'] . ' ' . $values[$key]['original']);
                }
            }
        }
        return $nm ?? null;
    }
}
