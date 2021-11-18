<?php

declare(strict_types=1);

namespace App\Glossary;

use App\Model\Glossary\Term;

class GlossaryBuilder
{
    /**
     * @param array|Term[] $terms
     * @param int<0, max>  $split
     */
    public function build(array $terms, int $split = 3): array
    {
        $minLength = ceil(\count($terms) / $split);
        $columns = array_fill(0, $split, []);
        $index = 0;
        $counter = 0;

        foreach ($this->sortByLetter($terms) as $letter => $group) {
            if ($counter >= $minLength) {
                ++$index;
                $counter = 0;
            }

            $columns[$index][$letter] = $group;
            $counter += \count($group);
        }

        return $columns;
    }

    private function sortByLetter(array $terms): array
    {
        $alphabet = [];

        foreach ($terms as $term) {
            $letter = $this->getLetter($term);

            if (!isset($alphabet[$letter])) {
                $alphabet[$letter] = [];
            }

            $alphabet[$letter][] = $term;
        }

        ksort($alphabet);

        return $alphabet;
    }

    public function getLetter(Term $term): string
    {
        if (preg_match('/^[a-z]/i', $term->name) === 1) {
            return strtolower($term->name[0]);
        }

        return '0';
    }
}
