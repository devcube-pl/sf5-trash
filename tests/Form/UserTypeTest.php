<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Testowanie formularzy
 * @see https://symfony.com/doc/current/form/unit_testing.html
 */
class UserTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'username' => 'test',
            'email' => 'test2@asdf.pl',
            'password' => ['first' => 'testowe', 'second' => 'testowe'],
            'fullName' => 'Test testowy'
        ];

        $entity = new User();
        $form = $this->factory->create(UserType::class, $entity);

        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());

        $expected = new User();
        $expected->setUsername($formData['username']);
        $expected->setEmail($formData['email']);
        $expected->setPassword($formData['password']['first']);
        $expected->setFullName($formData['fullName']);

        $this->assertEquals($expected, $entity);
    }
}
