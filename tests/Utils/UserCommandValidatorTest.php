<?php

namespace App\Tests\Utils;

use App\Utils\UserCommandValidator;
use PHPUnit\Framework\TestCase;

class UserCommandValidatorTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = new UserCommandValidator();
    }

    public function testValidateEmailEmpty()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Email nie może być pusty.');
        $this->validator->validateEmail(null);
    }

    public function testValidateEmailInvalid()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Email jest nieprawidłowy!!!');
        $this->validator->validateEmail('agsidfgausdgfuagdsyuf');
    }
}
