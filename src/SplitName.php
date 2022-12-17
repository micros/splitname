<?php

declare(strict_types=1);

namespace Micrositios\Names\App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Micrositios\Names\App\Models\Term;


class SplitName
{
    private $cleaner;
    private $tokenizer;
    private $tagger;
    public $isChanged = false;
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
    }
    public function split(string $fullName): array
    {

        $keys = Term::groupBy('type')->get()->pluck('type');

        dump($keys);

        $cleanName = $this->cleaner->clean($fullName);
        $this->isChanged = $cleanName !== $fullName;
        $tokenizedName = $this->tokenizer->tokenize($cleanName);
        $parts = $this->tagger->tag($tokenizedName);
        return $parts;
    }
    public function getIsChanged(): bool
    {
        return $this->isChanged;
    }
}
