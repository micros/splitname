<?php

declare(strict_types=1);

namespace Micros\Names\App\migrations;

use Micros\Names\App\migrations\RulesMigration;
use Micros\Names\App\migrations\SustitutionsMigration;
use Micros\Names\App\migrations\TermsMigration;
use Micros\Names\App\Models\Lesson;
use Micros\Names\App\Models\Rule;
use Micros\Names\App\Models\Sustitution;
use Micros\Names\App\Models\Term;
use voku\helper\ASCII;

class Load
{
    public function loadTerms()
    {
        new TermsMigration();

        include_once __DIR__ . "/../database/male.php";
        include_once __DIR__ . "/../database/female.php";
        include_once __DIR__ . "/../database/last.php";
        include_once __DIR__ . "/../database/conector.php";

        foreach ($terms as $key => $list) {
            foreach ($list as $term) {
                $k = $key;
                $gender = null;
                if ($key === 'M' || $key === 'F') {
                    $k = 'N';
                    $gender = $key;
                }
                $cleanTerm = $this->cleanPart($term);
                // Since the relation [term, key] is unique
                if (!Term::where('term', $cleanTerm)->where('type', $k)->exists()) {
                    $t = new Term();
                    $t->term = $cleanTerm;
                    $t->gender = $key;
                    $t->type = $k;
                    $t->gender = $gender;
                    $t->canonical = $term;
                    $t->save();
                }
            }
        }
    }
    public function loadRules()
    {
        new RulesMigration();

        include_once __DIR__ . "/../database/rule.php";

        foreach ($rules as $rule => $distribution) {
            if (!Rule::where('rule', $rule)->exists() && strlen($rule) === strlen($distribution)) {
                $r = new Rule();
                $r->rule = $rule;
                $r->distribution = $distribution;
                $r->save();
            }
        }
    }
    public function loadSustitutions()
    {
        new SustitutionsMigration();

        include_once __DIR__ . "/../database/sustitution.php";

        foreach ($sustitutions as $origin => $rule) {
            if (!Sustitution::where('origin', $origin)->exists() && Rule::where('rule', $rule)->exists() && strlen($rule) === strlen($origin)) {
                $r = new Sustitution();
                $r->origin = $origin;
                $r->rule = $rule;
                $r->save();
            }
        }
    }
    public function loadLessons()
    {
        new LessonsMigration();

        include_once __DIR__ . "/../database/lesson.php";

        foreach ($lessons as $rule => $type) {
            if (!Lesson::where('rule', $rule)->exists()) {
                $l = new Lesson();
                $l->rule = $rule;
                $l->type = $type;
                $l->save();
            }
        }
    }
    private function cleanPart(string $part): string
    {
        $part = preg_replace("/[^A-Za-záéíóúÁÉÍÓÚñÑüÜ ]/", '', $part);
        $part = ASCII::to_transliterate($part);
        $part = mb_strtolower($part, 'UTF-8');
        return $part;
    }
}
