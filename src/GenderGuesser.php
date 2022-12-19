<?php

declare(strict_types=1);

namespace Micros\Names\App;

class GenderGuesser
{
    public function guess(array $nm): string
    {
        $gender = null;
        $male = ['o', 'e'];
        $female = ['a', 'i', 'u'];

        if (array_key_exists('PN', $nm) && isset($nm['PN'])) {
            $test = substr($nm['PN'], -1);
            if (in_array($test, $male)) {
                $gender = 'M';
            }
            if (!$gender && in_array($test, $female)) {
                $gender = 'F';
            }
        }

        if (!$gender && array_key_exists('SN', $nm) && isset($nm['SN'])) {
            $test = substr($nm['SN'], -1);
            if (in_array($test, $male)) {
                $gender = 'M';
            }
            if (!$gender && in_array($test, $female)) {
                $gender = 'F';
            }
        }

        return $gender ?? 'I';
    }
}
