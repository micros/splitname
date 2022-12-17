<?php

declare(strict_types=1);

namespace Micrositios\Names\App;

use voku\helper\UTF8;

final class NameCleaner
{
    public function clean(string $name): string
    {
        // Remove duplicate spaces
        $name = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $name);
        // Remove non printable characters
        $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name);
        // Cleanup non UTF8 characters
        $name = UTF8::cleanup($name);
        $name = trim($name);

        return $name;
    }
}
