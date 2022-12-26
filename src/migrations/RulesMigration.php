<?php

declare(strict_types=1);

namespace Micros\Names\App\migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class RulesMigration
{
    public function __construct()
    {
        Capsule::schema()->dropIfExists('sustitutions');
        Capsule::schema()->dropIfExists('rules');

        Capsule::schema()->create('rules', function ($table) {
            $table->increments('id');
            $table->string('rule')->unique();
            $table->string('distribution');
            $table->timestamps(6);
        });
    }
}
