<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SzkolenieCreateUserCommand extends Command
{
    protected static $defaultName = 'szkolenie:create-user';
    protected static $defaultDescription = 'Add a short description for your command';


    private $io;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    private $validator;

    /**
     * SzkolenieCreateUserCommand constructor.
     * @param  EntityManagerInterface  $entityManager
     * @param  UserRepository  $userRepository
     * @param  UserPasswordHasherInterface  $passwordHasher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Nazwa uzyszkodnika')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'Jesli ustawiona flaga admin to wtedy user bedzie adminem')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $email = $this->io->ask('Podaj maila', null);
        $password = $this->io->askHidden('Hasło (znaki będą ukryte)', null);
        $fullName = $this->io->ask('Imię i nazwisko', null);
        $isAdmin = $input->getOption('admin');

        /**
         * @todo Walidacja
         */

        $user = new User();
        $user->setFullName($fullName);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(
            sprintf(
                '%s został poprawnie utworzony! Nazwa: %s, e-mail: %s',
                $isAdmin ? 'Administrator' : 'Użytkownik',
                $user->getUsername(),
                $user->getEmail()
            )
        );

        return Command::SUCCESS;
    }
}
