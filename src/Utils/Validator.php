<?php


namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class Validator
{
    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $username)) {
            throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('Пароль не может быть пустым.');
        }

        if (mb_strlen(trim($plainPassword)) < 6) {
            throw new InvalidArgumentException('Пароль должен быть длинной 6 символов.');
        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('The email can not be empty.');
        }

        if (false === mb_strpos($email, '@')) {
            throw new InvalidArgumentException('The email should look like a real email.');
        }

        return $email;
    }

    public function validateUUID(?string $uuid): string
    {
        if (empty($uuid)) {
            throw new InvalidArgumentException('Логин (номер телефона) не может быть пустым!');
        }
        if ($uuid[0] !== '9') {
            throw new InvalidArgumentException('Логин должен начинаться с 9');
        }

        if (strlen($uuid) !== 10) {
            throw new InvalidArgumentException('Логин должен содержать 10 цифр');
        }

        return $uuid;
    }

    public function validateFullName(?string $fullName): string
    {
        if (empty($fullName)) {
            throw new InvalidArgumentException('Имя не может быть пустым.');
        }

        return $fullName;
    }
}