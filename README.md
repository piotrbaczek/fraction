# fraction
Fraction is a PHP library for doing a fraction operation in a
human-like way. Normally when you do mathematical operations in
any programming language you get approximations, presented in a decimal form.

## Fraction class
This class represents human like fraction operations
1. Simple addition
```php
use pbaczek\fraction\Fraction;

// 1/2 or 0.5
$fractionHalf = new Fraction(1, 2);

// 3/17 or 0,17647...
$fractionThreeSeventeenth = new Fraction(3, 17);

$additionResult = Fraction::from($fractionHalf);
$additionResult->add($fractionThreeSeventeenth);

// 23/34
echo $additionResult;
// 0.68
echo $additionResult->getRealValue();
```
2. Subtraction
```php
use pbaczek\fraction\Fraction;

// -1/2
$negativeHalf = new Fraction(-1, 2);
// 1/2
$positiveHalf = new Fraction(1, 2);

$subtractionResult = Fraction::from($negativeHalf);
$subtractionResult->subtract($positiveHalf);
// -1
echo $subtractionResult;
// -1
echo $subtractionResult->getRealValue();
```
3. Multiplication
```php
use pbaczek\fraction\Fraction;

// 2/3
$twoEleventh = new Fraction(2, 11);

$minusThreeSeventeenth = new Fraction(-3, 17);

$multiplicationResult = Fraction::from($twoEleventh);
$multiplicationResult->multiply($minusThreeSeventeenth);

// -6/187
echo $multiplicationResult;
// -0.03
echo $multiplicationResult->getRealValue();
```
4. Division
```php
use pbaczek\fraction\Fraction;

// 2
$two = new Fraction(2);

// 1/2
$oneHalf = new Fraction(1, 2);

$divisionResult = Fraction::from($two);
$divisionResult->divide($oneHalf);

// 4
echo $divisionResult;
// 4
echo $divisionResult->getRealValue();
```

## MFraction class
This class utilizes https://en.wikipedia.org/wiki/Big_M_method
