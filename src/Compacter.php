<?php

declare(strict_types=1);

namespace Micros\Names\App;

final class Compacter
{
    public function compact(array $values): array
    {
        if (!in_array('C', array_column($values, 'type'))) {
            return $values;
        }

        $prev = null;
        $newValues = [];
        foreach ($values as $value) {
            if ($prev === 'C' && $value['type'] === 'C') {
                $index = count($newValues) - 1;
                $original = $values[$index]['original'] . ' ' . $value['original'];
                $modified = $values[$index]['modified'] . ' ' . $value['modified'];
                $newValues[$index] = ['original' => $original, 'modified' => $modified, 'type' => $value['type']];
            } else {
                $newValues[] = ['original' => $value['original'], 'modified' => $value['modified'], 'type' => $value['type']];
            }
            $prev = $value['type'];
        }
        return $newValues;
    }
}
