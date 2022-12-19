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
    private $pattern;
    private $genderGuesser;
    public $isChanged = false;
    public $terms;
    public function __construct()
    {
        $this->cleaner = new NameCleaner();
        $this->tokenizer = new Tokenizer();
        $this->tagger = new Tagger();
        $this->compacter = new Compacter();
        $this->classifier = new Classifier();
        $this->pattern = new Pattern();
        $this->genderGuesser = new GenderGuesser();

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
        $tokenizedName = $this->tokenizer->tokenize($cleanedName);
        $taggedName = $this->tagger->tag($tokenizedName, $this->terms);
        $compactedName = $this->compacter->compact($taggedName);

        $patterns = $this->pattern->get($compactedName);

        $classified = $this->classifier->classify($compactedName, $patterns[1]);

        if ($classified && !isset($classified['gender'])) {
            $classified['gender'] = $this->genderGuesser->guess($classified);
            $classified['guess-gender'] = true;
        }

        $object = [];
        $object['original'] = $fullName;
        $object['clean'] = $cleanedName;
        $object['is_changed'] = $cleanedName !== $fullName;
        $object['pattern'] = $patterns[0];
        $object['final-pattern'] = $patterns[1];
        $object['tagged-name'] = $compactedName;
        $object['classified'] = $classified;
        return $object;
    }
    public function init(): void
    {
        $t = new Load();
        $t->loadTerms();
        $this->terms = Term::all()->toArray();
    }
    private function getPattern(array $values): string
    {
        $structure = '';
        foreach ($values as $part) {
            $structure .= $part['type'];
        }
        return $structure;
    }
}
