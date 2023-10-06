<?php

namespace App\Command\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:create-user';

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    /**
     * configure
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to create user...')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputArgument('isAdmin', InputArgument::REQUIRED, 'is admin'),
            ));
    }

    /**
     * execute command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $isAdmin = $input->getArgument('isAdmin');
        $user = new User();

        $user->setEmail($email);
        $encodedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($encodedPassword);
        $user->setCreatedAt(new \DateTimeImmutable());

        if ($isAdmin) {
            $user->setRoles(['ROLE_SUPER_ADMIN']);
        }
        try {
            $this->userRepository->add($user, true);
        } catch (\Exception $e) {
            $output->writeln('<error>'. $e->getMessage(). '</error>');
            return Command::FAILURE;
        }

        $output->writeln(sprintf( $isAdmin ? 'Created <comment>admin</comment> user <comment>%s</comment>' :'Created user <comment>%s</comment>', $email));
        return Command::SUCCESS;
    }

    /**
     * Interact
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose a email: ');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }
                if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    throw new \Exception('Email is not valid');
                }
                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password: ');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }
                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        if (!$input->getArgument('isAdmin')) {
            $question = new ConfirmationQuestion('Is this user an admin? (y/n): ', false);
            $questions['isAdmin'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}