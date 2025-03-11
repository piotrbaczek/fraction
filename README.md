# fraction
Fraction is a PHP library for doing a fraction operation in a
human-like way. Normally when you do mathematical operations in
any programming language you get approximations, presented in a decimal form.

## Fraction class
This class represents human like fraction operations
1. Simple addition
```php
// 1/2 or 0.5
$fractionHalf = new pbaczek\fraction\Fraction(1, 2);

// 3/17 or 0,17647...
$fractionThreeSeventeenth = new \pbaczek\fraction\Fraction(3, 17);

$additionResult = clone $fractionHalf;
$additionResult->add($fractionThreeSeventeenth);

// 23/34
echo $additionResult;
// 0.68
echo $additionResult->getRealValue();
```
2. Subtraction
```php
// -1/2
$negativeHalf = new \pbaczek\fraction\Fraction(-1, 2);
// 1/2
$positiveHalf = new \pbaczek\fraction\Fraction(1, 2);

$subtractionResult = clone $negativeHalf;
$subtractionResult->subtract($positiveHalf);
// -1
echo $subtractionResult;
// -1
echo $subtractionResult->getRealValue();
```
3. Multiplication
```php
// 2/3
$twoEleventh = new \pbaczek\fraction\Fraction(2, 11);

$minusThreeSeventeenth = new \pbaczek\fraction\Fraction(-3, 17);

$multiplicationResult = clone $twoEleventh;
$multiplicationResult->multiply($minusThreeSeventeenth);

// -6/187
echo $multiplicationResult;
// -0.03
echo $multiplicationResult->getRealValue();
```
4. Division
```php
// 2
$two = new \pbaczek\fraction\Fraction(2);

// 1/2
$oneHalf = new \pbaczek\fraction\Fraction(1, 2);

$divisionResult = clone $two;
$divisionResult->divide($oneHalf);

// 4
echo $divisionResult;
// 4
echo $divisionResult->getRealValue();
```

## MFraction class
This class utilizes https://en.wikipedia.org/wiki/Big_M_method