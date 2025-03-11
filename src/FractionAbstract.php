<?php

namespace pbaczek\fraction;

use InvalidArgumentException;
use pbaczek\fraction\Dictionaries\Sign;
use pbaczek\fraction\Exceptions\NegativeDenominatorException;
use pbaczek\fraction\Exceptions\ZeroDenominatorException;
use pbaczek\fraction\Math\Math;

/**
 * Class FractionAbstract
 * @package pbaczek\fraction
 */
abstract class FractionAbstract
{
    private int $numerator;

    private int $denominator;


    public function __construct(int $numerator, int $denominator = 1)
    {
        $this->validateDenominator($denominator);

        $this->numerator = $numerator;
        $this->denominator = $denominator;
        $this->reduction();
    }

    public function getNumerator(): int
    {
        return $this->numerator;
    }

    public function getDenominator(): int
    {
        return $this->denominator;
    }

    /**
     * @return Sign
     */
    public function getSign(): Sign
    {
        return $this->numerator >= 0 ? Sign::NON_NEGATIVE : Sign::NEGATIVE;
    }

    /**
     * @param int $numerator
     * @return $this
     */
    public function setNumerator(int $numerator): self
    {
        $this->numerator = $numerator;
        $this->reduction();

        return $this;
    }

    /**
     * Set numerator without triggering reduction
     * @param int $numerator
     * @return $this
     */
    protected function setNumeratorWithoutReduction(int $numerator): self
    {
        $this->numerator = $numerator;
        return $this;
    }

    /**
     * @param int $denominator
     * @return $this
     */
    public function setDenominator(int $denominator): self
    {
        $this->validateDenominator($denominator);

        $this->denominator = $denominator;
        $this->reduction();

        return $this;
    }

    /**
     * Set denominator without triggering reduction
     * @param int $denominator
     * @return $this
     */
    protected function setDenominatorWithoutReduction(int $denominator): static
    {
        $this->validateDenominator($denominator);
        $this->denominator = $denominator;
        return $this;
    }

    /**
     * Change sign of fraction
     * @return void
     */
    public function changeSign(): void
    {
        if ($this->equals(0)) {
            return;
        }

        $this->numerator = -$this->numerator;
    }

    /**
     * Tests if Fraction is an Integer
     * @return bool
     */
    public function isFraction(): bool
    {
        return $this->getDenominator() != 1;
    }

    /**
     * Returns true if fraction is negative
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->numerator < 0;
    }

    /**
     * Returns true if fraction is not negative
     * @return bool
     */
    public function isNonNegative(): bool
    {
        return $this->numerator >= 0;
    }

    /**
     * Validate denominator
     * @param int $denominator
     */
    protected function validateDenominator(int $denominator): void
    {
        if ($denominator === 0) {
            throw new ZeroDenominatorException($denominator);
        }

        if ($denominator < 0) {
            throw new NegativeDenominatorException($denominator);
        }
    }

    /**
     * Get real value
     * @param int $precision
     * @return float
     */
    public function getRealValue(int $precision = 2): float
    {
        return round($this->getNumerator() / $this->getDenominator(), abs($precision));
    }

    /**
     * Print object
     * @return string
     */
    public function __toString(): string
    {
        $numeratorPart = $this->getNumerator();

        if ($this->getDenominator() === 1) {
            return $numeratorPart;
        }

        return $numeratorPart . '/' . $this->getDenominator();
    }

    /**
     * @param FractionAbstract|int|float $fractionAbstract
     * @return bool
     */
    public function equals($fractionAbstract): bool
    {
        if (is_int($fractionAbstract) === true) {
            return $this->getRealValue() === $fractionAbstract;
        }

        if (is_float($fractionAbstract) === true) {
            return abs($this->getRealValue() - $fractionAbstract) <= 0.0000001;
        }

        if ($fractionAbstract instanceof FractionAbstract) {
            return $fractionAbstract->getNumerator() === $this->getNumerator() && $fractionAbstract->getDenominator() === $this->getDenominator();
        }

        throw new InvalidArgumentException('invalid parameter');
    }

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

    /**
     * Divide real part
     * @param FractionAbstract $fractionAbstract
     * @return void
     */
    protected function divideRealPart(FractionAbstract $fractionAbstract): void
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

    /**
     * Add
     * @param FractionAbstract $fractionAbstract
     */
    abstract public function add(FractionAbstract $fractionAbstract): void;

    /**
     * Subtract
     * @param FractionAbstract $fractionAbstract
     */
    abstract public function subtract(FractionAbstract $fractionAbstract): void;

    /**
     * Divide
     * @param FractionAbstract $fractionAbstract
     */
    abstract public function divide(FractionAbstract $fractionAbstract): void;

    /**
     * Multiply
     * @param FractionAbstract $fractionAbstract
     */
    abstract public function multiply(FractionAbstract $fractionAbstract): void;
}