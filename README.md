# fraction
Fraction is a PHP library for doing a fraction operation in a
human-like way. Normally when you do mathematical operations in
any programming language you get approximations, presented in a decimal form.

## Fraction class
This class represents human like fraction operations
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