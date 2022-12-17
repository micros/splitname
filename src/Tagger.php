<?php

declare(strict_types=1);

namespace Micrositios\Names\App;

final class Tagger
{
    public function tag(array $name): array
    {
        $types = ['N', 'L', 'C'];
        foreach ($name as $part) {
            $parts[] = [$part, $types[rand(0, count($types) - 1)]];
        }
        return $parts;
    }
}
