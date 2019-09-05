<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Notifications\MailService;

class NotifyTestingSendCommand extends Command
{
    protected static $defaultName = 'notify:testing:send';
    protected $mail;

    public function __construct(MailService $mail)
    {
        parent::__construct();
        $this->mail = $mail;
    }

    protected function configure()
    {
        $this->setDescription('Отправка уведомления авторам, чьи задачи на тестировании');
    }

    /**
     * Выполнение таски
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->mail->sendEmail('Тестовое сообщение', 'is.malozemov@ya.ru','Какой-то текст.');
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
