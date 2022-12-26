<?php

declare(strict_types=1);

namespace Micros\Names\App\migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class SamplesMigration
{
    public function __construct()
    {
        Capsule::schema()->dropIfExists('samples');

        Capsule::schema()->create('samples', function ($table) {
            $table->increments('id');
            $table->string('term');
            $table->string('type');
            $table->string('gender')->nullable();
            $table->string('canonical')->nullable();
            $table->string('signature');
            $table->timestamps(6);
            $table->unique(['term', 'signature']);
        });
    }
}
