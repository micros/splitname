<?php

declare(strict_types=1);

namespace Micrositios\Names\App;

final class Tokenizer
{
    public function tokenize(string $value): array
    {
        return explode(' ', $value);
    }
}
