<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class UserNewCommand extends Command
{
    protected static $defaultName = 'user:new';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $validator;
    private $users;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, Validator $validator, UserRepository $users)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Creates users and stores them in the database')
            ->addArgument('uuid', InputArgument::OPTIONAL, 'The username of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addArgument('full-name', InputArgument::OPTIONAL, 'The full name of the new user')
            ->addOption('author', null, InputOption::VALUE_NONE, 'If set, the user is created as an Author')
            ->addOption('developer', null, InputOption::VALUE_NONE, 'If set, the user is created as an Developer')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (
            null !== $input->getArgument('uuid')
            && null !== $input->getArgument('password')
            && null !== $input->getArgument('full-name')
        ) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:add-user username password email@example.com',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);

        // Ask for the uuid if it's not defined
        $uuid = $input->getArgument('uuid');
        if (null !== $uuid) {
            $this->io->text(' > <info>UUID</info>: '.$uuid);
        } else {
            $uuid = $this->io->ask('UUID (Phone)', null, [$this->validator, 'validateUUID']);
            $input->setArgument('uuid', $uuid);
        }

        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: '.str_repeat('*', mb_strlen($password)));
        } else {
            $password = $this->io->askHidden('Password (your type will be hidden)', [$this->validator, 'validatePassword']);
            $input->setArgument('password', $password);
        }

        // Ask for the full name if it's not defined
        $fullName = $input->getArgument('full-name');
        if (null !== $fullName) {
            $this->io->text(' > <info>Full Name</info>: '.$fullName);
        } else {
            $fullName = $this->io->ask('Full Name', null, [$this->validator, 'validateFullName']);
            $input->setArgument('full-name', $fullName);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('new-user-command');

        $isAuthor = $input->getOption('author');
        $isDeveloper = $input->getOption('developer');

        $author = $isAuthor ? 'ROLE_AUTHOR' : null;
        $developer = $isDeveloper ? 'ROLE_DEVELOPER' : null;
        $role = $author ?? $developer ?? 'ROLE_USER';

        $uuid = $input->getArgument('uuid');
        $plainPassword = $input->getArgument('password');
        $fullName = $input->getArgument('full-name');

        $this->validateUserData($uuid, $plainPassword, $fullName);

        // create the user and encode its password
        $user = new User();
        $user->setUuid($uuid);
        $user->setFullName($fullName);
        $user->setRoles([$role]);
        $user->setPassword($plainPassword);

        // See https://symfony.com/doc/current/book/security.html#security-encoding-password
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created: %s (%s)', $isDeveloper ? 'Developer user' : 'User', $user->getUuid(), $user->getFullname()));

        $event = $stopwatch->stop('new-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }
    }

    private function validateUserData($uuid, $plainPassword, $fullName)
    {
        // first check if a user with the same username already exists.
        $existingUser = $this->users->findOneBy(['uuid' => $uuid]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" uuid.', $uuid));
        }

        // validate password and email if is not this input means interactive.
        $this->validator->validatePassword($plainPassword);
        $this->validator->validateUUID($uuid);
        $this->validator->validateFullName($fullName);
    }
}
