<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Micros\Names\App\migrations\Load;
use Micros\Names\App\Models\Lesson;
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
    public $lessons;
    public $init = false;
    public function __construct(array $settings = null)
    {

        $this->cleaner = new NameCleaner();
        $this->tokenizer = new Tokenizer();
        $this->tagger = new Tagger();
        $this->compacter = new Compacter();
        $this->classifier = new Classifier();
        $this->pattern = new Pattern();
        $this->genderGuesser = new GenderGuesser();

        $capsule = new Capsule;

        /**
         * Default connection
         */
        if (!$settings) {
            $settings['driver'] = 'sqlite';
            $settings['database'] = __DIR__ . '/database/data.sqlite';
            $settings['foreign_key_constraints'] = true;
        }
        /**
         * Create the database if not exists. For sqlite only
         */
        if ($settings['driver'] === 'sqlite' && !file_exists($settings['database'])) {
            fopen($settings['database'], 'w') or die("Can't create file" . $settings['database']);
        }

        // https://laracasts.com/discuss/channels/general-discussion/how-to-construct-illuminatedatabase-capsule-with-existing-pdo-connection
        $capsule->addConnection([
            'driver' => $settings['driver'],
            'database' => $settings['database'],
            'host' => isset($settings['host']) ? $settings['host'] : 'localhost',
            'username' => isset($settings['username']) ? $settings['username'] : '',
            'password' => isset($settings['password']) ? $settings['password'] : '',
            'collation' => isset($settings['collation']) ? $settings['collation'] : 'utf8_general_ci',
            'charset'   => isset($settings['charset']) ? $settings['charset'] : 'utf8',
            'strict'   => isset($settings['strict']) ? $settings['strict'] : false,
            'prefix' => isset($settings['prefix']) ? $settings['prefix'] : '',
            'foreign_key_constraints' => true,
        ], 'default');

        $capsule->setAsGlobal();

        $capsule->bootEloquent();

        if (!Capsule::schema()->hasTable('terms') || !Capsule::schema()->hasTable('rules') || !Capsule::schema()->hasTable('sustitutions')) {
            $this->init();
        }

        $this->terms = Term::get()->toArray();
        $this->rules = Rule::get()->pluck('distribution', 'rule')->toArray();
        $this->sustitutions = Sustitution::get()->pluck('rule', 'origin')->toArray();
        $this->lessons = Lesson::get()->pluck('type', 'rule')->toArray();
    }
    public function split(string $fullName): array
    {
        $cleanedName = $this->cleaner->clean($fullName);
        $tokenizedName = $this->tokenizer->tokenize($cleanedName);
        $taggedName = $this->tagger->tag($tokenizedName, $this->terms);
        $compactedName = $this->compacter->compact($taggedName, $this->lessons);

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
        $t->loadLessons();
    }
}
