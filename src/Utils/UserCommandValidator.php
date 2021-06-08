<?php

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

use function Symfony\Component\String\u;

class UserCommandValidator
{
    /**
     * @param  string|null  $email
     * @return string
     */
    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Email nie może być pusty.');
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email jest nieprawidłowy!!!');
        }

        return $email;
    }

    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('Nazwa użytkownika nie może być pusta');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $username)) {
            throw new InvalidArgumentException('Nazwa użytkownika może zawierać tylko małe litery i podkreślenia');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('Hasło nie może być puste');
        }

        if (u($plainPassword)->trim()->length() < 6) {
            throw new InvalidArgumentException('Hasło musi mieć przynajmniej 6 znaków');
        }

        return $plainPassword;
    }

    public function validateFullName(?string $fullName): string
    {
        if (empty($fullName)) {
            throw new InvalidArgumentException('Imię i nazwisko nie może być puste');
        }

        return $fullName;
    }
}
