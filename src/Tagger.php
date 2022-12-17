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
            if ($type = $this->searchByTerm($this->cleanPart($part), $terms)) {
                $fixedParts[] = [$part, $this->cleanPart($part), $type];
            }
        }
        return $fixedParts;
    }
    private function cleanPart(string $part): string
    {
        $part = preg_replace("/[^A-Za-záéíóúÁÉÍÓÚñÑ ]/", '', $part);
        $part = ASCII::to_transliterate($part);
        $part = mb_strtolower($part, 'UTF-8');
        return $part;
    }
    private function searchByTerm($id, $array)
    {
        foreach ($array as $key => $val) {
            if ($val['term'] === $id) {
                return $val['type'];
            }
        }
        return null;
    }
}
