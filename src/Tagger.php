<?php

declare(strict_types=1);

namespace Micrositios\Names\App;

use voku\helper\ASCII;

final class Tagger
{
    public function tag(array $name): array
    {
        $types = ['N', 'L', 'C'];
        foreach ($name as $part) {
            $type = $types[rand(0, count($types) - 1)];
            $parts[] = [$part, ASCII::to_transliterate(mb_strtolower($part, 'UTF-8')), $type];
        }
        return $parts;
    }
}
