<?php

declare(strict_types=1);

namespace Micrositios\Names\App\migrations;

use Micrositios\Names\App\migrations\TermMigration;
use Micrositios\Names\App\Models\Term;

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
                if (!Term::where('term', $term)->where('type', $k)->exists()) {
                    $t = new Term();
                    $t->term = $term;
                    $t->gender = $key;
                    $t->type = $k;
                    $t->gender = $gender;
                    $t->save();
                }
            }
        }
    }
}
