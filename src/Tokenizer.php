<?php

declare(strict_types=1);

namespace Micros\Splitname;

final class Tokenizer
{
    public function tokenize(string $value): array
    {
        return explode(' ', $value);
    }
}
