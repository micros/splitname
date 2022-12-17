<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Micros\Names\App\Models\Term;

final class Tokenizer
{
    public function tokenize(string $value): array
    {
        return explode(' ', $value);
    }
}
