<?php

declare(strict_types=1);

namespace Micros\Names\App;

use Micros\Names\App\Models\Term;

final class Compacter
{
    public function compact(array $values, array $lessons): array
    {
        $pattern = implode('', array_column($values, 'type'));

        if (array_key_exists($pattern, $lessons)) {
            foreach ($values as $part) {
                if ($part['type'] === 'X' && !Term::where('term', $part['modified'])->where('type', $lessons[$pattern])->exists()) {
                    $term = new Term();
                    $term->term = $part['modified'];
                    $term->type = $lessons[$pattern];
                    $term->gender = 'I';
                    $term->canonical = mb_strtolower($part['original'], 'UTF-8') !== $part['modified'] ? mb_strtolower($part['original'], 'UTF-8') : '';
                    $term->save();
                }
            }
        }

        $compactables = ['C'];

        // if (!array_intersect($compactables, array_column($values, 'type'))) {
        //     return $values;
        // }

        // Compactar todos los C en un solo C
        $prev = null;
        $tmpValues = [];
        foreach ($values as $value) {
            if (in_array($prev, $compactables)) {
                $index = count($tmpValues) - 1;
                $original = $tmpValues[$index]['original'] . ' ' . $value['original'];
                $modified = $tmpValues[$index]['modified'] . ' ' . $value['modified'];
                $tmpValues[$index] = ['original' => $original, 'modified' => $modified, 'type' => $value['type']];
            } else {
                $tmpValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
            }
            $prev = $value['type'];
        }
        $values = $tmpValues;

        // Compactar todos los CL como L
        $prev = null;
        $tmpValues = [];
        foreach ($values as $value) {
            if ($value['type'] === 'L' && $prev === 'C') {
                $index = count($tmpValues) - 1;
                $original = $tmpValues[$index]['original'] . ' ' . $value['original'];
                $modified = $tmpValues[$index]['modified'] . ' ' . $value['modified'];
                $tmpValues[$index] = ['original' => $original, 'modified' => $modified, 'type' => $value['type']];
            } else {
                $tmpValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
            }
            $prev = $value['type'];
        }
        $values = $tmpValues;

        // Compactar todos los CN como N
        $prev = null;
        $tmpValues = [];
        foreach ($values as $value) {
            if ($value['type'] === 'N' && $prev === 'C') {
                $index = count($tmpValues) - 1;
                $original = $tmpValues[$index]['original'] . ' ' . $value['original'];
                $modified = $tmpValues[$index]['modified'] . ' ' . $value['modified'];
                $tmpValues[$index] = ['original' => $original, 'modified' => $modified, 'type' => $value['type']];
            } else {
                $tmpValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
            }
            $prev = $value['type'];
        }
        $values = $tmpValues;

        // Reemplazar el primer N => A y el primer L => B
        $flagA = null;
        $flagB = null;
        $tmpValues = [];
        foreach ($values as $value) {
            if ($value['type'] === 'N' && !$flagA) {
                $value['type'] = 'A';
                $flagA = true;
            }
            if ($value['type'] === 'L' && !$flagB) {
                $value['type'] = 'B';
                $flagB = true;
            }
            $tmpValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
        }
        $values = $tmpValues;

        // Compactar todos los N y L restantes
        $prev = null;
        $tmpValues = [];
        $compactables = ['N', 'L'];
        foreach ($values as $value) {
            if (in_array($prev, $compactables) && $prev === $value['type']) {
                $index = count($tmpValues) - 1;
                $original = $tmpValues[$index]['original'] . ' ' . $value['original'];
                $modified = $tmpValues[$index]['modified'] . ' ' . $value['modified'];
                $tmpValues[$index] = ['original' => $original, 'modified' => $modified, 'type' => $value['type']];
            } else {
                $tmpValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
            }
            $prev = $value['type'];
        }
        $values = $tmpValues;

        // Devolver los valores de A y B a los originales
        $tmpValues = [];
        foreach ($values as $value) {
            if ($value['type'] === 'A') {
                $value['type'] = 'N';
            }
            if ($value['type'] === 'B') {
                $value['type'] = 'L';
            }
            $tmpValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
        }
        $values = $tmpValues;

        return $tmpValues;
    }
}
