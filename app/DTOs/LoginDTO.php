<?php
namespace App\DTOs;
use App\Http\Requests\Auth\LoginRequest;

class LoginDTO
{
    public function __construct(
        private readonly string $email,
        private readonly string $password
    ) {}

    public static function fromRequest(LoginRequest $request): self
    {
        return new self(
            email: $request->validated('email'),
            password: $request->validated('password')
        );
    }

    public function credentials(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
