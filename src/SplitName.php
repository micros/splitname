<?php

declare(strict_types=1);

namespace Micros\Splitname;

use Micros\Splitname\Classifier;
use Micros\Splitname\Compacter;
use Micros\Splitname\Database\migrations\Load;
use Micros\Splitname\GenderGuesser;
use Micros\Splitname\Learn;
use Micros\Splitname\Models\SplitLesson;
use Micros\Splitname\Models\SplitRule;
use Micros\Splitname\Models\SplitSustitution;
use Micros\Splitname\Models\SplitTerm;
use Micros\Splitname\NameCleaner;
use Micros\Splitname\Pattern;
use Micros\Splitname\Tagger;
use Micros\Splitname\Tokenizer;

class SplitName
{
    private $threshold = 3;
    private $cleaner;
    private $tokenizer;
    private $tagger;
    private $learn;
    private $compacter;
    private $classifier;
    private $pattern;
    private $genderGuesser;
    public $isChanged = false;
    public $terms;
    public $rules;
    public $sustitutions;
    public $lessons;
    public $init = false;
    public function __construct()
    {
        $this->cleaner = new NameCleaner();
        $this->tokenizer = new Tokenizer();
        $this->tagger = new Tagger();
        $this->compacter = new Compacter();
        $this->learn = new Learn();
        $this->classifier = new Classifier();
        $this->pattern = new Pattern();
        $this->genderGuesser = new GenderGuesser();

        $this->init();
        $this->terms = SplitTerm::get()->toArray();
        $this->rules = SplitRule::get()->pluck('distribution', 'rule')->toArray();
        $this->sustitutions = SplitSustitution::get()->pluck('rule', 'origin')->toArray();
        $this->lessons = SplitLesson::get()->pluck('type', 'rule')->toArray();
    }
    public function split(string $fullName): array
    {
        $cleanedName = $this->cleaner->clean($fullName);
        $tokenizedName = $this->tokenizer->tokenize($cleanedName);
        $taggedName = $this->tagger->tag($tokenizedName, $this->terms);
        $sample = $this->learn->createSample($taggedName, $this->lessons);
        $compactedName = $this->compacter->compact($taggedName);

        $patterns = $this->pattern->get($compactedName, $this->sustitutions);

        $classified = $this->classifier->classify($compactedName, $patterns[1], $this->rules);

        if ($classified && !isset($classified['gender'])) {
            $classified['gender'] = $this->genderGuesser->guess($classified);
            $classified['guess-gender'] = true;
        }
        // Fix gender in learned names
        if ($sample?->type === 'N' && isset($classified['gender']) && in_array($classified['gender'], ['M', 'F'])) {
            $sample->gender = $classified['gender'];
            $sample->save();
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
        if (!SplitTerm::count() || !SplitRule::count()) {
            $t = new Load();
            $t->loadTerms();
            $t->loadRules();
            $t->loadSustitutions();
            $t->loadLessons();
            $t->loadSamples();
        }
    }
    public function process(): void
    {
        $report = new ProcessSample($this->threshold);
        $report->process();
    }
    public function setThreshold(int $threshold): SplitName
    {
        $this->threshold = $threshold;
        return $this;
    }
}
