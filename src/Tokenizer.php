<?php

declare(strict_types=1);

namespace Micros\Names\App;

final class Tokenizer
{
    public function tokenize(string $value): array
    {
        return explode(' ', $value);
    }
}
