<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;
use Micros\Names\App\migrations\Load;
use Micros\Names\App\Models\Rule;
use Micros\Names\App\Models\Sustitution;
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
    public $rules;
    public $sustitutions;
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

        if (!Capsule::schema()->hasTable('terms') || !Capsule::schema()->hasTable('rules') || !Capsule::schema()->hasTable('sustitutions')) {
            $this->init();
        }

        $this->terms = Term::all()->toArray();
        $this->rules = Rule::get()->pluck('distribution', 'rule')->toArray();
        $this->sustitutions = Sustitution::get()->pluck('rule', 'origin')->toArray();
    }
    public function split(string $fullName): array
    {
        $cleanedName = $this->cleaner->clean($fullName);
        $tokenizedName = $this->tokenizer->tokenize($cleanedName);
        $taggedName = $this->tagger->tag($tokenizedName, $this->terms);
        $compactedName = $this->compacter->compact($taggedName);

        $patterns = $this->pattern->get($compactedName, $this->sustitutions);

        $classified = $this->classifier->classify($compactedName, $patterns[1], $this->rules);

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
        $t->loadRules();
        $t->loadSustitutions();

        $this->terms = Term::all()->toArray();
        $this->rules = Rule::get()->pluck('distribution', 'rule')->toArray();
        $this->sustitutions = Sustitution::get()->pluck('rule', 'origin')->toArray();
    }
}
