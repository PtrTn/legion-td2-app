<?php

declare(strict_types=1);

namespace App\Enum;

use ValueError;

enum ArmorType: string
{
    case Swift = 'swift';
    case Natural = 'natural';
    case Fortified = 'fortified';
    case Arcane = 'arcane';
    case Immaterial = 'immaterial';

    public static function fromValue(string $value): self
    {
        foreach (self::cases() as $enum) {
            if(strtolower($value) === $enum->value ){
                return $enum;
            }
        }
        throw new ValueError("$value is not a valid backing value for enum " . self::class);
    }
}
