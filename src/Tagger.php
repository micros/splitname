<?php

declare(strict_types=1);

namespace Micros\Names\App;

use voku\helper\ASCII;

final class Tagger
{
    public function tag(array $name): array
    {
        $types = ['N', 'L', 'C'];
        foreach ($name as $part) {

            $type = $types[rand(0, count($types) - 1)];

            $parts[] = [$part, $this->cleanPart($part), $type];
        }
        return $parts;
    }
    private function cleanPart(string $part): string
    {
        $part = preg_replace("/[^A-Za-záéíóúÁÉÍÓÚñÑ ]/", '', $part);
        $part = ASCII::to_transliterate($part);
        $part = mb_strtolower($part, 'UTF-8');
        return $part;
    }
}
