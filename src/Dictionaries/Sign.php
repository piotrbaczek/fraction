<?php

namespace pbaczek\fraction\Dictionaries;

/**
 * Enum Sign
 * @package pbaczek\fraction\Dictionaries
 */
enum Sign: string
{
    case NON_NEGATIVE = '+';
    case NEGATIVE = '-';
}