<?php

namespace eloquentFilter\QueryFilter\Queries\DB;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\DB\Builder;

/**
 * Class Fuzzy.
 */
class Fuzzy extends BaseClause
{
    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        $pattern = $this->createFuzzyPattern($this->values['fuzzy']);
        return $query->whereRaw("LOWER($this->filter) LIKE LOWER(?) ", $pattern);
    }

    /**
     * Create a fuzzy pattern for LIKE query
     * 
     * @param string $value
     * @return string
     */
    protected function createFuzzyPattern(string $value): string
    {
        $chars = str_split($value);
        $pattern = '%';
        
        foreach ($chars as $char) {
            $variations = $this->getCharacterVariations($char);
            if (count($variations) > 1) {
                $pattern .= '[' . implode('', $variations) . ']';
            } else {
                $pattern .= $variations[0];
            }
        }
        
        $pattern .= '%';
        return $pattern;
    }

    /**
     * Get possible variations for a character
     * 
     * @param string $char
     * @return array
     */
    protected function getCharacterVariations(string $char): array
    {
        $variations = [
            'a' => ['a', '@', '4'],
            'e' => ['e', '3'],
            'i' => ['i', '1', '!'],
            'o' => ['o', '0'],
            's' => ['s', '5', '$'],
            't' => ['t', '7'],
            'g' => ['g', '9'],
            'l' => ['l', '1'],
            'z' => ['z', '2'],
            'b' => ['b', '8'],
        ];

        $char = strtolower($char);
        return $variations[$char] ?? [$char];
    }
} 