<?php

declare(strict_types=1);

namespace Micrositios\Names\App;

class SplitName
{
    private $cleaner;
    private $tokenizer;
    private $tagger;
    public function __construct()
    {
        $this->cleaner = new NameCleaner();
        $this->tokenizer = new Tokenizer();
        $this->tagger = new Tagger();
    }
    public function split(string $value): array
    {
        $name = $this->cleaner->clean($value);
        $name = $this->tokenizer->tokenize($name);
        $parts = $this->tagger->tag($name);
        return $parts;
    }
}
