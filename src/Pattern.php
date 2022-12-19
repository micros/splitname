<?php

declare(strict_types=1);

namespace Micros\Names\App;

final class Pattern
{
    private $sustitutionRules = [
        'LXNX' => 'LLNN',
        'NCLCX' => 'NCLCL',
        'NCLXL' => 'NCLCL',
        'NCNLCX' => 'NCNLCL',
        'NCNLXL' => 'NCNLCL',
        'NCNXCL' => 'NCNLCL',
        'NCX' => 'NCL',
        'NCXCL' => 'NCNCL',
        'NCXLCL' => 'NCNLCL',
        'NCXLX' => 'NCNLL',
        'NIL' => 'NNL',
        'NILI' => 'NNLL',
        'NLI' => 'NLL',
        'NLN' => 'NLL',
        'NLX' => 'NLL',
        'NLXL' => 'NLCL',
        'NNLI' => 'NNLL',
        'NNLX' => 'NNLL',
        'NNX' => 'NNL',
        'NNXL' => 'NNLL',
        'NNXX' => 'NNLL',
        'NX' => 'NL',
        'NXCX' => 'NNCL',
        'NXL' => 'NLL',
        'NXLL' => 'NNLL',
        'NXLN' => 'NNLL',
        'NXLX' => 'NNLL',
        'NXNLCL' => 'NCNLCL',
        'NXX' => 'NLL',
        'NXXL' => 'NNLL',
        'NXXX' => 'NNLL',
        'XCL' => 'NCL',
        'XCLCL' => 'NCLCL',
        'XCNL' => 'NCNL',
        'XCNLCL' => 'NCNLCL',
        'XCNX' => 'NCNL',
        'XCNXX' => 'NCNLL',
        'XCX' => 'NCL',
        'XCXLCL' => 'NCNLCL',
        'XCXLL' => 'NCNLL',
        'XCXXX' => 'NCNLL',
        'XIL' => 'NNL',
        'XL' => 'NL',
        'XLCL' => 'NLCL',
        'XLL' => 'NLL',
        'XLX' => 'NLL',
        'XNCL' => 'NNCL',
        'XNCX' => 'NNCL',
        'XNL' => 'NNL',
        'XNLI' => 'NNLL',
        'XNLL' => 'NNLL',
        'XNX' => 'NNL',
        'XNXL' => 'NNLL',
        'XNXX' => 'NNLL',
        'XX' => 'NL',
        'XXCL' => 'NLCL',
        'XXL' => 'NNL',
        'XXLL' => 'NNLL',
        'XXLX' => 'NNLL',
        'XXX' => 'NNL',
        'XXXL' => 'NNLL',
        'XXXX' => 'NNLL',
        'NNCXCX' => 'NNCLCL',
        'NNCNXX' => 'NNCNLL',
    ];
    public function get(array $values): array
    {
        $pattern = $this->getPattern($values);
        $sustitution = str_replace('Z', 'X', $pattern);

        if (array_key_exists($sustitution, $this->sustitutionRules)) {
            $sustitution = $this->sustitutionRules[$sustitution];
        }

        return [$pattern, $sustitution];
    }
    private function getPattern(array $values): string
    {
        $pattern = '';
        foreach ($values as $part) {
            $pattern .= $part['type'];
        }
        return $pattern;
    }
}
