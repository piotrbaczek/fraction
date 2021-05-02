<?php

namespace pbaczek\fraction;

use InvalidArgumentException;

/**
 * Class Fraction
 * @package pbaczek\fraction
 */
class Fraction extends FractionAbstract
{
    /**
     * @inheritDoc
     * @param Fraction $fractionAbstract
     * @return void
     */
    public function add($fractionAbstract): void
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
     * @param Fraction $fractionAbstract
     * @return void
     */
    public function subtract($fractionAbstract): void
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
     * @param Fraction $fractionAbstract
     * @return void
     */
    public function divide($fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->divideRealPart($fractionAbstract);

        $this->reduction();
    }

    /**
     * @inheritDoc
     * @param Fraction $fractionAbstract
     * @return void
     */
    public function multiply($fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->setNumeratorWithoutReduction($this->getNumerator() * $fractionAbstract->getNumerator());
        $this->setDenominatorWithoutReduction($this->getDenominator() * $fractionAbstract->getDenominator());
        $this->reduction();
    }
}