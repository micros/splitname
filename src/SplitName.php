<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Micros\Names\App\migrations\Load;
use Micros\Names\App\Models\Term;

class SplitName
{
    private $cleaner;
    private $tokenizer;
    private $tagger;
    public $isChanged = false;
    public $terms;
    public function __construct()
    {
        $this->cleaner = new NameCleaner();
        $this->tokenizer = new Tokenizer();
        $this->tagger = new Tagger();

        $capsule = new Capsule;

        $capsule->addConnection([
            "driver" => "sqlite",
            "database" => __DIR__ . "/database/data.sqlite",
            "prefix" => "",
            'foreign_key_constraints' => true
        ], 'default');

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
        $this->terms = Term::all()->toArray();
    }
    public function split(string $fullName): array
    {
        $cleanName = $this->cleaner->clean($fullName);
        $this->isChanged = $cleanName !== $fullName;
        $tokenizedName = $this->tokenizer->tokenize($cleanName);
        $parts = $this->tagger->tag($tokenizedName, $this->terms);
        return $parts;
    }
    public function getIsChanged(): bool
    {
        return $this->isChanged;
    }
    public function init(): void
    {
        $t = new Load();
        $t->loadTerms();
    }
}
