<?php

declare(strict_types=1);

namespace Micros\Names\App\migrations;

use Micros\Names\App\migrations\TermMigration;
use Micros\Names\App\Models\Term;
use voku\helper\ASCII;

class Load
{
    public function loadTerms()
    {
        new TermMigration();

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
                if (!Term::where('term', $this->cleanPart($term))->where('type', $k)->exists()) {
                    $t = new Term();
                    $t->term = $this->cleanPart($term);
                    $t->gender = $key;
                    $t->type = $k;
                    $t->gender = $gender;
                    $t->save();
                }
            }
        }
    }
    private function cleanPart(string $part): string
    {
        $part = preg_replace("/[^A-Za-záéíóúÁÉÍÓÚñÑ ]/", '', $part);
        $part = ASCII::to_transliterate($part);
        $part = mb_strtolower($part, 'UTF-8');
        return $part;
    }
}
