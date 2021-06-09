<?php

namespace App\Tests\Command;


use App\Command\SzkolenieCreateUserCommand;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SzkolenieCreateUserCommandTest extends KernelTestCase
{
    private $userData = [
        'username' => 'testowy_user',
        'email' => 'testowy@example.com',
        'password' => '123456',
        'full-name' => 'Uzytkownik Testowy',
    ];

    /**
     * Ciekawostka
     */
    protected function setUp(): void
    {
        exec('stty 2>&1', $output, $exitcode);
        $isSttySupported = 0 === $exitcode;

        if ('Windows' === PHP_OS_FAMILY || !$isSttySupported) {
            $this->markTestSkipped('`stty` is required to test this command.');
        }
    }

    /**
     * @dataProvider isAdminDataProvider
     */
    public function testCreateUserInteractive(bool $isAdmin): void
    {
        /*
         * @see https://symfony.com/doc/current/components/console/helpers/questionhelper.html#testing-a-command-that-expects-input
         */

        /*
         * Wywolanie komendy to: szkolenie:create-user [--admin] <username>
         * Argument username jest wymagany
         */

        $data = $this->userData;
        $arguments = ['username' => array_shift($data)];

        if ($isAdmin) {
            $arguments['--admin'] = true;
        }

        $this->executeCommand(
            $arguments,
            array_values($data) // dla interactive juz bez username bo wyzej jest array_shift
        );

        $this->assertUserCreated($isAdmin);
    }

    public function isAdminDataProvider(): ?\Generator
    {
        yield [false];
        yield [true];
    }

    private function assertUserCreated(bool $isAdmin): void
    {
        $container = self::getContainer();

        /** @var \App\Entity\User $user */
        $user = $container->get(UserRepository::class)->findOneByEmail($this->userData['email']);
        $this->assertNotNull($user);

        $this->assertSame($this->userData['full-name'], $user->getFullName());
        $this->assertSame($this->userData['username'], $user->getUsername());
        $this->assertTrue($container->get('security.user_password_hasher')->isPasswordValid($user, $this->userData['password']));
        $this->assertContains($isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER', $user->getRoles());
    }

    private function executeCommand(array $arguments, array $inputs = []): void
    {
        self::bootKernel();

        $command = self::getContainer()->get(SzkolenieCreateUserCommand::class);
        $command->setApplication(new Application(self::$kernel));

        $commandTester = new CommandTester($command);
        $commandTester->setInputs($inputs);
        $commandTester->execute($arguments);
    }
}
