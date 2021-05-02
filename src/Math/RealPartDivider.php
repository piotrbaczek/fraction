<?php

namespace pbaczek\fraction\Math;

use pbaczek\fraction\FractionAbstract;

/**
 * Trait RealPartDivider
 * @package pbaczek\fraction\Math
 */
trait RealPartDivider
{
    /**
     * Divide real part
     * @param FractionAbstract $fractionAbstract
     */
    private function divideRealPart($fractionAbstract)
    {
        $newNumerator = $this->getNumerator() * $fractionAbstract->getDenominator();
        if ($newNumerator < 0) {
            $this->changeSign();
        }

        $this->setNumeratorWithoutReduction(abs($newNumerator));

        $newDenominator = $this->getDenominator() * $fractionAbstract->getNumerator();

        if ($newDenominator < 0) {
            $this->changeSign();
        }

        $this->setDenominatorWithoutReduction(abs($newDenominator));
    }
}