<?php

declare(strict_types=1);

namespace Micrositios\Names\App;

use Micrositios\Names\App\Models\Term;

final class Tokenizer
{
    public function tokenize(string $value): array
    {
        return explode(' ', $value);
    }
}
