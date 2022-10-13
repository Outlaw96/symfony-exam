<?php

namespace App\Service;

class Common
{
    /**
     * Copy the content of array parameter and return the copied array.
     *
     * @param array<int|float|string > $array
     *
     * @return array<int|float|string >
     */
    public static function boo(array $array): array
    {
        $result = [];
        array_walk_recursive($array, function ($a) use (&$result): void {
            $result[] = $a;
        });

        return $result;
    }

    /**
     * Add to array1 new entry with array2['k'] value as index and array2['v'] as value.
     *
     * @param array<int|float|string > $array1
     * @param array<int|float|string > $array2
     *
     * @return array<int|float|string >
     */
    public static function foo(array $array1, array $array2): array
    {
        return [...$array1, $array2['k'] => $array2['v']];
    }

    /**
     * Retourne le nombre d'index de l'array1 n'étant pas des valeurs dans l'array2 :D.
     *
     * @param array<int|float|string > $array1
     * @param array<int|float|string > $array2
     */
    public static function bar(array $array1, array $array2): bool
    {
        $r = array_filter(array_keys($array1), fn ($k) => !in_array($k, $array2));

        return 0 == count($r);
    }
}
