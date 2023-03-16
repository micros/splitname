<?php

declare(strict_types=1);

namespace Micros\Splitname;

use Micros\Splitname\PartCleaner;

final class Tagger
{
    private $partCleaner;
    public function __construct()
    {
        $this->partCleaner = new PartCleaner();
    }
    public function tag(array $parts, $terms): array
    {
        $fixedParts = [];

        foreach ($parts as $part) {
            $original = $part;
            $part = $this->partCleaner->clean($part);

            $type = 'X';
            $tmp = $this->searchTerm($part, $terms);
            if (count($tmp) === 1) {
                $type = $tmp[0];
            }
            if (count($tmp) > 1) {
                $type = 'Z';
            }
            if ($type === 'X' && strlen($part) === 1 && ctype_alpha($part)) {
                $type = 'I';
            }
            $fixedParts[] = ['original' => $original, 'modified' => $part, 'type' => $type];
        }
        return $fixedParts;
    }
    private function searchTerm($value, $array): array
    {
        $type = [];
        foreach ($array as $val) {
            if ($val['term'] === $value) {
                $type[] = $val['type'];
            }
        }
        return $type;
    }
}
