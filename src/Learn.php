<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Micros\Names\App\Models\Sample;
use Micros\Names\App\Models\Term;

final class learn
{

    public function create(array $values, array $lessons): ?Sample
    {
        $pattern = implode('', array_column($values, 'type'));
        $sample = null;
        if (array_key_exists($pattern, $lessons)) {
            $signature = implode('-', array_column($values, 'modified'));
            foreach ($values as $part) {
                if (
                    $part['type'] === 'X'
                    && !Term::where('term', $part['modified'])->where('type', $lessons[$pattern])->exists()
                    && !Sample::where('term', $part['modified'])->where('signature', $signature)->exists()
                ) {
                    $sample = new Sample();
                    $sample->term = $part['modified'];
                    $sample->type = $lessons[$pattern];
                    $sample->canonical = mb_strtolower($part['original'], 'UTF-8') !== $part['modified'] ? mb_strtolower($part['original'], 'UTF-8') : '';
                    $sample->signature = $signature;
                    $sample->save();
                }
            }
        }
        return $sample;
    }
}
