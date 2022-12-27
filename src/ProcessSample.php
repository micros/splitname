<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Micros\Names\App\Models\Sample;
use Micros\Names\App\Models\Term;

final class ProcessSample
{
    public function process(): void
    {
        $samples = Sample::groupBy(['term', 'type', 'gender', 'canonical'])->selectRaw('term, type, gender, canonical, count(*) as total')->get();
        foreach ($samples as $sample) {
            if ($sample->total > 1) {
                if (!Term::where('term', $sample->term)->where('type', $sample->type)->exists()) {
                    $term = new Term();
                    $term->term = $sample->term;
                    $term->type = $sample->type;
                    $term->gender = $sample->gender;
                    $term->canonical = $sample->canonical;
                    if ($term->save()) {
                        Sample::where('term', $sample->term)->where('type', $sample->type)->where('gender', $sample->gender)->where('canonical', $sample->canonical)->delete();
                    }
                }
            }
        }
    }
}
