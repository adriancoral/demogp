<?php

namespace App\DataTransferObject;

use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

class PasswordCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $context): mixed
    {
        return Hash::make($value);
    }
}
