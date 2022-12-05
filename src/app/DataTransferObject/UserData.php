<?php
namespace App\DataTransferObject;

use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class UserData  extends Data
{

    public function __construct(
        public string $name,
        public string $email,
        #[WithCast(PasswordCast::class)]
        public string $password,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
