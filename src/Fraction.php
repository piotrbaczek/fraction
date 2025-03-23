<?php

namespace pbaczek\fraction;

use InvalidArgumentException;
use Override;

/**
 * Class Fraction
 * @package pbaczek\fraction
 */
class Fraction extends FractionAbstract
{
    /**
     * @inheritDoc
     * @param FractionAbstract $fractionAbstract
     * @return void
     */
    public function add(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->setNumeratorWithoutReduction($this->getNumerator() * $fractionAbstract->getDenominator() + $this->getDenominator() * $fractionAbstract->getNumerator());
        $this->setDenominatorWithoutReduction($this->getDenominator() * $fractionAbstract->getDenominator());
        $this->reduction();
    }

    /**
     * @inheritDoc
     * @param FractionAbstract $fractionAbstract
     * @return void
     */
    public function subtract(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->setNumeratorWithoutReduction($this->getNumerator() * $fractionAbstract->getDenominator() - $this->getDenominator() * $fractionAbstract->getNumerator());
        $this->setDenominatorWithoutReduction($this->getDenominator() * $fractionAbstract->getDenominator());
        $this->reduction();
    }

    /**
     * @inheritDoc
     * @param FractionAbstract $fractionAbstract
     * @return void
     */
    public function divide(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->divideRealPart($fractionAbstract);

        $this->reduction();
    }

    /**
     * @inheritDoc
     * @param FractionAbstract $fractionAbstract
     * @return void
     */
    public function multiply(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->setNumeratorWithoutReduction($this->getNumerator() * $fractionAbstract->getNumerator());
        $this->setDenominatorWithoutReduction($this->getDenominator() * $fractionAbstract->getDenominator());
        $this->reduction();
    }

    /**
     * @return int|float
     */
    #[Override] public function getValue(): int|float
    {
        return $this->getNumerator() / $this->getDenominator();
    }

    /**
     * @param int $precision
     * @return int|float
     */
    #[Override] public function getRealValue(int $precision = 2): int|float
    {
        return round($this->getNumerator() / $this->getDenominator(), abs($precision));
    }
}