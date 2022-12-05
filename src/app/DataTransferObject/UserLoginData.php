<?php
namespace App\DataTransferObject;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class UserLoginData  extends Data
{

    public function __construct(
        public string $email,
        public string $password,
    ) {
    }

    public static function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
