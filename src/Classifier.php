<?php

declare(strict_types=1);

namespace Micros\Names\App;

final class Classifier
{
    private $rules = [
        'N' => '1',
        'NN' => '12',
        'NCL' => '133',
        'NCLL' => '1334',
        'NCNL' => '1223',
        'NCNLL' => '12234',
        'NCLCL' => '13344',
        'NCNLCL' => '122344',
        'NL' => '13',
        'NLCL' => '1344',
        'NLL' => '134',
        'NNL' => '123',
        'NNLL' => '1234',
        'NNNL' => '1223',

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

    private $sustitutionRules = [
        'NCX' => 'NCL',
        'NIL' => 'NNL',
        'NILI' => 'NNLL',
        'NLI' => 'NLL',
        'NLN' => 'NLL',
        'NLX' => 'NLL',
        'NLXL' => 'NLCL',
        'NNLX' => 'NNLL',
        'NNX' => 'NNL',
        'NNXL' => 'NNLL',
        'NNXX' => 'NNLL',
        'NX' => 'NL',
        'NXL' => 'NLL',
        'NXLL' => 'NNLL',
        'NXLX' => 'NNLL',
        'NXLN' => 'NNLL',
        'NXX' => 'NLL',
        'NXXL' => 'NNLL',
        'NXXX' => 'NNLL',
        'NZ' => 'NL',
        'XCNXX' => 'NCNLL',
        'XCX' => 'NCL',
        'XCXXX' => 'NCNLL',
        'XL' => 'NL',
        'XLCL' => 'NLCL',
        'XLX' => 'NLL',
        'XNL' => 'NNL',
        'XNLI' => 'NNLL',
        'XNLL' => 'NNLL',
        'XNX' => 'NNL',
        'XNXL' => 'NNLL',
        'XNXX' => 'NNLL',
        'XX' => 'NL',
        'XXL' => 'NNL',
        'XLL' => 'NLL',
        'XXLL' => 'NNLL',
        'XXLX' => 'NNLL',
        'XXX' => 'NNL',
        'XXXL' => 'NNLL',
        'XXXX' => 'NNLL',
        'XZ' => 'NL',
        'ZIL' => 'NNL',
        'ZL' => 'NL',
        'ZX' => 'NL',
        'ZZL' => 'NNL',
        'XCNLCL' => 'NCNLCL',
        'NXNLCL' => 'NCNLCL',
        'NCXLCL' => 'NCNLCL',
        'NCNXCL' => 'NCNLCL',
        'NCNLXL' => 'NCNLCL',
        'NCNLCX' => 'NCNLCL',
        'XCLCL' => 'NCLCL',
        'NCXCL' => 'NCNCL',
        'NCLXL' => 'NCLCL',
        'NCLCX' => 'NCLCL',
        'NCXLX' => 'NCNLL',
        'LXNX' => 'LLNN',
    ];

    public function classify(array $values): array
    {
        $pattern = $this->getPattern($values);

        if (array_key_exists($pattern, $this->sustitutionRules)) {
            $pattern = $this->sustitutionRules[$pattern];
        }

        $nm['PN'] = null;
        $nm['SN'] = null;
        $nm['PA'] = null;
        $nm['SA'] = null;

        if (array_key_exists($pattern, $this->rules)) {
            $v = str_split($this->rules[$pattern]);
            foreach ($v as $key => $value) {
                if ($value === '1') {
                    $nm['PN'] = trim($nm['PN'] . ' ' . $values[$key]['original']);
                }
                if ($value === '2') {
                    $nm['SN'] = trim($nm['SN'] . ' ' . $values[$key]['original']);
                }
                if ($value === '3') {
                    $nm['PA'] = trim($nm['PA'] . ' ' . $values[$key]['original']);
                }
                if ($value === '4') {
                    $nm['SA'] = trim($nm['SA'] . ' ' . $values[$key]['original']);
                }
            }
        }
        return $nm;
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
