<?php

declare(strict_types=1);

namespace Micros\Names\App\migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class SustitutionsMigration
{
    public function __construct()
    {
        Capsule::schema()->dropIfExists('sustitutions');

        Capsule::schema()->create('sustitutions', function ($table) {
            $table->increments('id');
            $table->string('origin')->unique();
            $table->string('rule');
            $table->timestamps(6);
            $table->foreign('rule')->references('rule')->on('rules')->onDelete('cascade');
        });
    }
}
