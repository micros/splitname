<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Micros\Names\App\Models\Term;

final class Classifier
{
    private $rules = [
        'N' => '1',
        'NN' => '12',
        'NCL' => '133',
        'NNCL' => '1233',
        'NCLL' => '1334',
        'NCNL' => '1223',
        'NCNLL' => '12234',
        'NCLCL' => '13344',
        'NCNLCL' => '122344',
        'NNCLCL' => '123344',
        'NNCNLL' => '122234',
        'NL' => '13',
        'NLCL' => '1344',
        'NLL' => '134',
        'NNL' => '123',
        'NNLL' => '1234',
        'NNNL' => '1223',
        'NLLCL' => '13444',
        'NNLCL' => '12344',

        'L' => '3',
        'LL' => '34',
        'LCLN' => '3441',
        'LCLNN' => '34412',
        'LN' => '31',
        'LNCN' => '3122',
        'LNN' => '312',
        'LLN' => '341',
        'LLNN' => '3412',
    ];
    public function classify(array $values, string $pattern): ?array
    {

        if (array_key_exists($pattern, $this->rules)) {
            $v = str_split($this->rules[$pattern]);

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
    private function getPattern(array $values): string
    {
        $structure = '';
        foreach ($values as $part) {
            $structure .= $part['type'];
        }
        return $structure;
    }
}
