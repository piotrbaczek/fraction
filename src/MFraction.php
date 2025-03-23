<?php

namespace pbaczek\fraction;

use InvalidArgumentException;
use Override;
use pbaczek\fraction\Dictionaries\Sign;
use pbaczek\fraction\Math\Math;

/**
 * Class MFraction
 * @package pbaczek\fraction
 */
class MFraction extends FractionAbstract
{
    private const string M_SIGN = 'M';

    private int $mNumerator;
    private int $mDenominator;

    /**
     * MFraction constructor.
     * @param int $numerator
     * @param int $denominator
     * @param int $mNumerator
     * @param int $mDenominator
     */
    public function __construct(int $numerator, int $denominator = 1, int $mNumerator = 0, int $mDenominator = 1)
    {
        $this->validateDenominator($mDenominator);

        $this->mNumerator = $mNumerator;
        $this->mDenominator = $mDenominator;

        parent::__construct($numerator, $denominator);
    }

    /**
     * @param int $mNumerator
     * @return $this
     */
    public function setMNumerator(int $mNumerator): self
    {
        $this->mNumerator = $mNumerator;
        $this->reduction();
        return $this;
    }

    /**
     * @param int $mDenominator
     * @return $this
     */
    public function setMDenominator(int $mDenominator): self
    {
        $this->mDenominator = $mDenominator;
        $this->reduction();
        return $this;
    }

    /**
     * Set M numerator without triggering reduction
     * @param int $mNumerator
     * @return $this
     */
    protected function setMNumeratorWithoutReduction(int $mNumerator): self
    {
        $this->mNumerator = $mNumerator;
        return $this;
    }

    /**
     * Set M denominator without triggering reduction
     * @param int $mDenominator
     * @return $this
     */
    protected function setMDenominatorWithoutReduction(int $mDenominator): self
    {
        $this->validateDenominator($mDenominator);
        $this->mDenominator = $mDenominator;
        return $this;
    }

    /**
     * @return int
     */
    public function getMNumerator(): int
    {
        return $this->mNumerator;
    }

    /**
     * @return int
     */
    public function getMDenominator(): int
    {
        return $this->mDenominator;
    }

    /**
     * @inheritDoc
     * @return void
     */
    public function changeSign(): void
    {
        parent::changeSign();
        if ($this->mNumerator === 0) {
            return;
        }

        $this->mNumerator = -$this->mNumerator;
    }

//    /**
//     * Return float value of FractionAbstract
//     * @param int $precision
//     * @return float
//     */
//    public function getRealValue(int $precision = 2): float
//    {
//        if ($this->mNumerator !== 0) {
//            return $this->mNumerator >= 0 ? PHP_INT_MAX : PHP_INT_MIN;
//        }
//
//        return parent::getRealValue($precision);
//    }

    /**
     * Return string of FractionAbstract
     * @return string
     */
    public function __toString(): string
    {
        $realPart = parent::__toString();

        if ($this->mNumerator === 0) {
            return $realPart;
        }

        if ($this->mDenominator === 1) {
            return $realPart . ($this->mNumerator >= 0 ? Sign::NON_NEGATIVE->value : '') . $this->mNumerator . self::M_SIGN;
        }

        return $realPart . ($this->mNumerator >= 0 ? Sign::NON_NEGATIVE->value : '') . $this->mNumerator . '/' . $this->mDenominator . self::M_SIGN;
    }

    /**
     * Add
     * @param FractionAbstract $fractionAbstract
     */
    public function add(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->setNumeratorWithoutReduction($this->getNumerator() * $fractionAbstract->getDenominator() + $this->getDenominator() * $fractionAbstract->getNumerator());
        $this->setDenominatorWithoutReduction($this->getDenominator() * $fractionAbstract->getDenominator());

        $this->setMNumeratorWithoutReduction($this->getMNumerator() * $fractionAbstract->getMDenominator() + $this->getMDenominator() * $fractionAbstract->getMNumerator());
        $this->setMDenominatorWithoutReduction($this->getMDenominator() * $fractionAbstract->getMDenominator());

        $this->reduction();
    }

    /**
     * Subtract
     * @param FractionAbstract $fractionAbstract
     */
    public function subtract(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->setNumeratorWithoutReduction($this->getNumerator() * $fractionAbstract->getDenominator() - $this->getDenominator() * $fractionAbstract->getNumerator());
        $this->setDenominatorWithoutReduction($this->getDenominator() * $fractionAbstract->getDenominator());

        $this->setMNumeratorWithoutReduction($this->getMNumerator() * $fractionAbstract->getMDenominator() - $this->getMDenominator() * $fractionAbstract->getMNumerator());
        $this->setMDenominatorWithoutReduction($this->getMDenominator() * $fractionAbstract->getMDenominator());

        $this->reduction();
    }

    /**
     * Divide
     * @param FractionAbstract $fractionAbstract
     */
    public function divide(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->divideRealPart($fractionAbstract);
        $this->divideMPart($fractionAbstract);

        $this->reduction();
    }

    /**
     * Divide M Part of MFraction
     * @param MFraction $fractionAbstract
     */
    private function divideMPart(MFraction $fractionAbstract): void
    {
        $newMNumerator = $this->getMNumerator() * $fractionAbstract->getMDenominator();
        if ($newMNumerator < 0) {
            $this->changeSign();
        }

        $this->setMNumeratorWithoutReduction(abs($newMNumerator));

        $newMDenominator = $this->getMDenominator() * $fractionAbstract->getMNumerator();

        if ($newMDenominator < 0) {
            $this->changeSign();
        }

        $this->setMDenominatorWithoutReduction(abs($newMDenominator));
    }

    /**
     * Reduce numerator and denominator
     * @return void
     */
    protected function reduction(): void
    {
        if ($this->getNumerator() == 0) {
            $this->setDenominatorWithoutReduction(1);
            $this->reduceMPart();
            return;
        }

        if (abs($this->getNumerator()) == 1 || $this->getDenominator() == 1) {
            $this->reduceMPart();
            return;
        }

        $this->reduceMPart();
        parent::reduction();
    }

    /**
     * Reduce M Part of the MFraction
     * @return void
     */
    private function reduceMPart(): void
    {
        if ($this->getMNumerator() == 0) {
            $this->setMDenominatorWithoutReduction(1);
            return;
        }

        if (abs($this->getMNumerator()) == 1 || $this->getMDenominator() == 1) {
            return;
        }

        $greatestCommonDivisor = Math::greatestCommonDivisor($this->getMNumerator(), $this->getMDenominator());
        $this->setMNumeratorWithoutReduction($this->getMNumerator() / $greatestCommonDivisor);
        $this->setMDenominatorWithoutReduction($this->getMDenominator() / $greatestCommonDivisor);
    }

    /**
     * Multiply
     * @param FractionAbstract $fractionAbstract
     */
    public function multiply(FractionAbstract $fractionAbstract): void
    {
        if ($fractionAbstract instanceof self === false) {
            throw new InvalidArgumentException('Only same class allowed');
        }

        $this->setNumeratorWithoutReduction($this->getNumerator() * $fractionAbstract->getNumerator());
        $this->setDenominatorWithoutReduction($this->getDenominator() * $fractionAbstract->getDenominator());

        $this->setMNumeratorWithoutReduction($this->getMNumerator() * $fractionAbstract->getMNumerator());
        $this->setMDenominatorWithoutReduction($this->getMDenominator() * $fractionAbstract->getMDenominator());

        $this->reduction();
    }

    #[Override] public function getValue(): int|float
    {
        if ($this->getMNumerator() === 0) {
            return $this->getNumerator() / $this->getDenominator();
        }

        return $this->getMNumerator() > 0 ? PHP_INT_MAX : PHP_INT_MIN;
    }

    #[Override] public function getRealValue(int $precision = 2): int|float
    {
        if ($this->getMNumerator() === 0) {
            return round($this->getNumerator() / $this->getDenominator(), abs($precision));
        }

        return $this->getMNumerator() > 0 ? PHP_INT_MAX : PHP_INT_MIN;
    }
}