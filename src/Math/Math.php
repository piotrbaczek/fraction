<?php

namespace pbaczek\fraction\Math;

/**
 * Class Math
 * @package pbaczek\fraction\Math
 */
final class Math
{
    /**
     * Finds GCD (Greatest Common Divisor) of two positive Integers
     * @static
     * @param Integer $a
     * @param Integer $b
     * @return Integer
     */
    public static function greatestCommonDivisor(int $a, int $b): int
    {
        return gmp_intval(gmp_gcd($a, $b));
    }

    /**
     * Math constructor.
     */
    private function __construct()
    {
    }
}