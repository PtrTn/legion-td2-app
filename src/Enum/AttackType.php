<?php

declare(strict_types=1);

namespace App\Enum;

use ValueError;

enum AttackType: string
{
    case Impact = 'impact';
    case Pierce = 'pierce';
    case Magic = 'magic';
    case Pure = 'pure';

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
