<?php

namespace App\Utils;

use RandomLib\Factory;

class TokenGenerator
{
    const LENGTH = 16;
    const VOCAB = 'ABCDEFGHIJKLMNOPQRSTUVXYZ0123456789';

    public static function generate(int $length = self::LENGTH, string $vocab = self::VOCAB): string
    {
        $generator = (new Factory())->getMediumStrengthGenerator();

        return $generator->generateString($length, $vocab);
    }
}