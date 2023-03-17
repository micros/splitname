<?php

declare(strict_types=1);

namespace Micros\Splitname;

use Micros\Splitname\Models\SplitSample;
use Micros\Splitname\Models\SplitTerm;

final class ProcessSample
{
    private $threshold;
    public function __construct(int $threshold)
    {
        $this->threshold = $threshold;
    }
    public function process(): void
    {
        // Solución temporal, eliminar nombres sin genero
        SplitSample::where('type', 'N')->where('gender', null)->delete();

        $samples = SplitSample::groupBy(['term', 'type', 'gender', 'canonical'])->selectRaw('term, type, gender, canonical, count(*) as total')->get();
        foreach ($samples as $sample) {
            if ($sample->total >= $this->threshold) {
                if (!SplitTerm::where('term', $sample->term)->where('type', $sample->type)->exists()) {
                    $term = new SplitTerm();
                    $term->term = $sample->term;
                    $term->type = $sample->type;
                    $term->gender = $sample->gender;
                    $term->canonical = $sample->canonical;
                    if ($term->save()) {
                        SplitSample::where('term', $sample->term)->where('type', $sample->type)->where('gender', $sample->gender)->where('canonical', $sample->canonical)->delete();
                    }
                }
            }
        }
    }
}
