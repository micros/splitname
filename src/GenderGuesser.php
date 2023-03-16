<?php

declare(strict_types=1);

namespace Micros\Splitname;

class GenderGuesser
{
    /**
     * Very very very rudimentary function for guess the gender
     * Please improve
     * @param array $nm
     * @return string
     */
    public function guess(array $nm): string
    {
        $gender = null;
        $male = ['o', 'l', 'n', 's'];
        $female = ['a'];

        if (array_key_exists('first-name', $nm) && isset($nm['first-name'])) {
            $test = substr($nm['first-name'], -1);
            if (in_array($test, $male)) {
                $gender = 'M';
            }
            if (!$gender && in_array($test, $female)) {
                $gender = 'F';
            }
        }

        if (!$gender && array_key_exists('middle-name', $nm) && isset($nm['middle-name'])) {
            $test = substr($nm['middle-name'], -1);
            if (in_array($test, $male)) {
                $gender = 'M';
            }
            if (!$gender && in_array($test, $female)) {
                $gender = 'F';
            }
        }

        return $gender ?? 'U'; // Gender or undefined
    }
}
