<?php

declare(strict_types=1);

namespace Micros\Names\App;

final class Compacter
{
    public function compact(array $values): array
    {
        $compactablesTypes = ['C', 'I'];

        if (!array_intersect($compactablesTypes, array_column($values, 'type'))) {
            return $values;
        }

        foreach ($compactablesTypes as $compactableType) {
            $prev = null;
            $newValues = [];
            foreach ($values as $value) {
                if ($prev === $compactableType && $value['type'] === $compactableType) {
                    $index = count($newValues) - 1;
                    $original = $values[$index]['original'] . ' ' . $value['original'];
                    $modified = $values[$index]['modified'] . ' ' . $value['modified'];
                    $newValues[$index] = ['original' => $original, 'modified' => $modified, 'type' => $value['type']];
                } else {
                    $newValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
                }
                $prev = $value['type'];
            }
            $values = $newValues;
        }

        return $values;
    }
}
