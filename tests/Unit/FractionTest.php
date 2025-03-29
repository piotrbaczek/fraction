<?php

namespace pbaczek\fraction\tests\Unit;

use InvalidArgumentException;
use pbaczek\fraction\Dictionaries\Sign;
use pbaczek\fraction\Exceptions\NegativeDenominatorException;
use pbaczek\fraction\Exceptions\ZeroDenominatorException;
use pbaczek\fraction\Fraction;
use pbaczek\fraction\MFraction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

/**
 * Class FractionTest
 * @package pbaczek\fraction\tests\Unit
 */
class FractionTest extends TestCase
{
    private Fraction $fraction;

    /**
     * @inheritDoc
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->fraction = new Fraction(1, 2);
    }

    /**
     * Test basic functionality
     * @return void
     */
    public function testBasic(): void
    {
        $this->assertEquals(1, $this->fraction->getNumerator());
        $this->assertEquals(2, $this->fraction->getDenominator());
        $this->assertEquals(Sign::NON_NEGATIVE, $this->fraction->getSign());
        $this->assertEquals(0.5, $this->fraction->getRealValue());
        $this->assertEquals(0.5, $this->fraction->getValue());
        $this->assertTrue($this->fraction->isFraction());
    }

    /**
     * Test sign of a fraction changing
     * @return void
     */
    public function testChangingSign(): void
    {
        $this->fraction->changeSign();
        $this->assertEquals(Sign::NEGATIVE, $this->fraction->getSign());
        $this->fraction->changeSign();
        $this->assertEquals(Sign::NON_NEGATIVE, $this->fraction->getSign());

        $zero = new Fraction(0);
        $zero->changeSign();
        $this->assertEquals(Sign::NON_NEGATIVE, $zero->getSign());
    }

    /**
     * Test that reduction of numerator, denominator occurs
     * @return void
     */
    public function testFractionIsBeingReduced(): void
    {
        $fraction = new Fraction(5, 10);
        $this->assertEquals(1, $fraction->getNumerator());
        $this->assertEquals(2, $fraction->getDenominator());
    }

    /**
     * Test setting numerator runs reduction
     * @return void
     */
    public function testSettingNumerator(): void
    {
        $this->fraction->setNumerator(12);
        $this->assertEquals('6', $this->fraction->__toString());

        $this->fraction->setNumerator(0);
        $this->assertEquals('0', $this->fraction->__toString());

        $this->fraction = new Fraction(1, 2);
        $this->fraction->setNumerator(-12);
        $this->assertEquals('-6', $this->fraction->__toString());
    }

    /**
     * Test setting denominator only accepts positive integers
     * @return void
     */
    public function testSettingPositiveDenominator(): void
    {
        $this->fraction->setDenominator(6);
        $this->assertEquals('1/6', $this->fraction->__toString());
    }

    /**
     * Test setting denominator as Zero throws exception
     * @return void
     */
    public function testSettingZeroDenominator(): void
    {
        $this->expectException(ZeroDenominatorException::class);
        $this->expectExceptionMessage('0');

        $this->fraction->setDenominator(0);
    }

    /**
     * Test that setting negative denominator throws NegativeDenominatorException
     * @return void
     */
    public function testSettingNegativeDenominator(): void
    {
        $this->expectException(NegativeDenominatorException::class);
        $this->expectExceptionMessage('-12');

        $this->fraction->setDenominator(-12);
    }

    /**
     * Test adding fractions - fractions, whole number and negative number
     * @return void
     */
    public function testAddingTwoFractions(): void
    {
        $first = clone $this->fraction;
        $second = new Fraction(1, 3);
        $first->add($second);
        $this->assertEquals('5/6', $first->__toString());

        $first->add(new Fraction(1));
        $this->assertEquals('11/6', $first->__toString());

        $first->add(new Fraction(-1));
        $this->assertEquals('5/6', $first->__toString());

        $first->add(new Fraction(-11, 6));
        $this->assertEquals('-1', $first->__toString());
    }

    /**
     * Tests that subtracting two fractions works
     * @return void
     */
    public function testSubtractingTwoFractions(): void
    {
        $first = clone $this->fraction;
        $second = new Fraction(1, 2);
        $first->subtract($second);
        $this->assertEquals('0', $first->__toString());

        $first->subtract(new Fraction(1, 2));
        $this->assertEquals('-1/2', $first->__toString());

        $first->subtract(new Fraction(-1, 2));
        $this->assertEquals('0', $first->__toString());
    }

    /**
     * Tests that multiplying two fractions works
     * @return void
     */
    public function testMultiplyingTwoFractions(): void
    {
        $first = clone $this->fraction;
        $second = clone $this->fraction;
        $first->multiply($second);
        $this->assertEquals('1/4', $first->__toString());

        $first->multiply(new Fraction(1));
        $this->assertEquals('1/4', $first->__toString());

        $first->multiply(new Fraction(-4));
        $this->assertEquals('-1', $first->__toString());

        $first->multiply(new Fraction(0));
        $this->assertEquals('0', $first->__toString());
    }

    /**
     * Tests that dividing two fractions works
     * @return void
     */
    public function testDividingTwoFractions(): void
    {
        $this->expectException(ZeroDenominatorException::class);
        $this->expectExceptionMessage('0');

        $first = clone $this->fraction;
        $second = clone $this->fraction;
        $first->divide($second);
        $this->assertEquals('1', $first->__toString());

        $first->divide(new Fraction(-1, 2));
        $this->assertEquals('-2', $first->__toString());

        $zero = new Fraction(0);
        $this->assertEquals('0', $zero->__toString());

        $first->divide($zero);
    }

    /**
     * Tests that returning real value works on fractions, even when changing sings
     * @return void
     */
    public function testReturningRealValue(): void
    {
        $this->assertEquals(0.5, $this->fraction->getRealValue());

        $this->fraction->setNumerator(1);
        $this->fraction->setDenominator(3);
        $this->assertEquals(0.33, $this->fraction->getRealValue());
        $this->assertEquals(1 / 3, $this->fraction->getValue());

        $this->fraction->changeSign();
        $this->assertEquals(-0.33, $this->fraction->getRealValue());
        $this->assertEquals(-1 / 3, $this->fraction->getValue());
        $this->assertEquals(-1, $this->fraction->getNumerator());
        $this->assertEquals(3, $this->fraction->getDenominator());

        $this->fraction->multiply(new Fraction(3));
        $this->assertEquals(-1, $this->fraction->getRealValue());
        $this->assertEquals(-1, $this->fraction->getValue());
        $this->assertTrue($this->fraction->isNegative());
        $this->assertFalse($this->fraction->isNonNegative());

        $this->fraction->changeSign();
        $this->assertEquals(1, $this->fraction->getRealValue());
        $this->assertEquals(1, $this->fraction->getValue());
    }

    /**
     * Tests creating new Fraction from existing fraction
     * @return void
     */
    public function testCreatingFractionFromFraction(): void
    {
        $fraction = new Fraction(1, 3);

        $newFraction = Fraction::from($fraction);

        $fraction->setNumerator(2);

        $this->assertEquals(2, $fraction->getNumerator());
        $this->assertEquals(3, $fraction->getDenominator());
        $this->assertEquals(1, $newFraction->getNumerator());
        $this->assertEquals(3, $newFraction->getDenominator());
    }

    /**
     * @return void
     */
    public function testDivisionOfNegativeFractionByPositiveRealNumber(): void
    {
        $fraction = new Fraction(-5, 3);
        $fraction->divide(new Fraction(2));
        $this->assertEquals(-5, $fraction->getNumerator());
        $this->assertEquals(6, $fraction->getDenominator());
    }

    /**
     * Defined functions for
     * @return array
     */
    public static function definedFunctions(): array
    {
        return [
            [
                'add'
            ],
            [
                'subtract'
            ],
            [
                'multiply'
            ],
            [
                'divide'
            ]
        ];
    }

    #[DataProvider('definedFunctions')]
    #[TestDox('Tests that you can only perform functions on same class objects')]
    public function testAllMathFunctionsWorkOnlyOnSameObject(string $function): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only same class allowed');

        $this->fraction->{$function}(new MFraction(2));
    }
}