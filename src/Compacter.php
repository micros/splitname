<?php

declare(strict_types=1);

namespace Micros\Names\App;

final class Compacter
{
    public function compact(array $values): array
    {
        $compactables = ['C'];

        if (!array_intersect($compactables, array_column($values, 'type'))) {
            return $values;
        }

        // foreach ($compactables as $compactable) {
        $prev = null;
        $tmpValues = [];
        foreach ($values as $value) {
            // if (in_array($value['type'], $compactables) && $value['type'] === $prev) {
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
        // }

        return $values;
    }
}
