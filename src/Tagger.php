<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Micros\Names\App\Models\Term;
use voku\helper\ASCII;

final class Tagger
{
    public function tag(array $parts, $terms): array
    {
        $fixedParts = [];

        foreach ($parts as $part) {
            $original = $part;
            $part = $this->cleanTerm($part);

            $type = 'X';
            $tmp = $this->searchTerm($part, $terms);
            if (count($tmp) === 1) {
                $type = $tmp[0];
            }
            if (count($tmp) > 1) {
                $type = 'Z';
            }
            if ($type === 'X' && strlen($part) === 1 && ctype_alpha($part)) {
                $type = 'I';
            }
            $fixedParts[] = ['original' => $original, 'modified' => $part, 'type' => $type];
        }
        return $fixedParts;
    }
    private function cleanTerm(string $value): string
    {
        $value = preg_replace("/[^A-Za-záéíóúÁÉÍÓÚñÑ ]/", '', $value);
        $value = ASCII::to_transliterate($value);
        $value = mb_strtolower($value, 'UTF-8');
        return $value;
    }
    private function searchTerm($value, $array): array
    {
        $type = [];
        foreach ($array as $val) {
            if ($val['term'] === $value) {
                $type[] = $val['type'];
            }
        }
        return $type;
    }
}
