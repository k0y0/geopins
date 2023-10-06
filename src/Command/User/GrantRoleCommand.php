<?php

namespace App\Command\User;

use App\Repository\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GrantRoleCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:grant-role';

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(
        UserRepository $userRepository,
    ) {
        parent::__construct();
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
            ->setHelp('This command allows you to grant a role for user...')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('role', InputArgument::REQUIRED, 'The Role you want to grant'),
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
        $role = $input->getArgument('role');
        $user = $this->userRepository->findOneBy(['email' => $email,]);

        $user->setRoles(array_merge($user->getRoles(), [$role]));
        try {
            $this->userRepository->add($user, true);
        } catch (\Exception $e) {
            $output->writeln('<error>'. $e->getMessage(). '</error>');
            return Command::FAILURE;
        }

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

        if (!$input->getArgument('role')) {
            $question = new Question('Please choose a Role: ');
            $questions['role'] = $question;
        }


        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}