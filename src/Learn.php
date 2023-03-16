<?php

declare(strict_types=1);

namespace Micros\Splitname;

use Micros\Splitname\Models\SplitSample;
use Micros\Splitname\Models\SplitTerm;

final class Learn
{

    public function createSample(array $values, array $lessons): ?SplitSample
    {
        $pattern = implode('', array_column($values, 'type'));
        $sample = null;
        if (array_key_exists($pattern, $lessons)) {
            $signature = implode('-', array_column($values, 'modified'));
            foreach ($values as $part) {
                if (
                    $part['type'] === 'X'
                    && !SplitTerm::where('term', $part['modified'])->where('type', $lessons[$pattern])->exists()
                    && !SplitSample::where('term', $part['modified'])->where('signature', $signature)->exists()
                ) {
                    $sample = new SplitSample();
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
