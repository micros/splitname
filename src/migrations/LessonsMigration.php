<?php

declare(strict_types=1);

namespace Micros\Names\App\migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class LessonsMigration
{
    public function __construct()
    {
        Capsule::schema()->dropIfExists('lessons');

        Capsule::schema()->create('lessons', function ($table) {
            $table->increments('id');
            $table->string('rule')->unique();
            $table->string('type');
            $table->timestamps(6);
        });
    }
}
