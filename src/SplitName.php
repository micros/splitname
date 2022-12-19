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
    private $compacter;
    private $classifier;
    public $isChanged = false;
    public $terms;
    public function __construct()
    {
        $this->cleaner = new NameCleaner();
        $this->tokenizer = new Tokenizer();
        $this->tagger = new Tagger();
        $this->compacter = new Compacter();
        $this->classifier = new Classifier();

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
        $cleanedName = $this->cleaner->clean($fullName);
        $this->isChanged = $cleanedName !== $fullName;
        $tokenizedName = $this->tokenizer->tokenize($cleanedName);
        $taggedName = $this->tagger->tag($tokenizedName, $this->terms);
        $compactedName = $this->compacter->compact($taggedName);
        $classes = $this->classifier->classify($compactedName);
        return $classes;
    }
    public function init(): void
    {
        $t = new Load();
        $t->loadTerms();
        $this->terms = Term::all()->toArray();
    }
}
