<?php

namespace pbaczek\fraction\Math;

/**
 * Trait FractionMathHelper
 * @package pbaczek\fraction\Math
 */
trait FractionMathHelper
{
    /**
     * Reduce numerator and denominator
     * @return void
     */
    protected function reduction(): void
    {
        if ($this->getNumerator() == 0) {
            $this->setDenominatorWithoutReduction(1);
            return;
        }

        if (abs($this->getNumerator()) == 1 || $this->getDenominator() == 1) {
            return;
        }

        $greatestCommonDivisor = Math::greatestCommonDivisor($this->getNumerator(), $this->getDenominator());
        $this->setNumeratorWithoutReduction($this->getNumerator() / $greatestCommonDivisor);
        $this->setDenominatorWithoutReduction($this->getDenominator() / $greatestCommonDivisor);
    }
}