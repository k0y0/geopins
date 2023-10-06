<?php

namespace App\Service\Notification\Provider\Mail\Mailer;

use App\Service\Notification\Provider\Mail\MailProviderAbstract;
use App\Service\Notification\Provider\Result\Result;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Mailer extends MailProviderAbstract
{
    public const PROVIDER_KEY = 1;

    public const PROVIDER_NAME = 'mailer';

    /**
     * @var TransportInterface
     */
    private TransportInterface $smtpMailer;

    /**
     * mailer default Email
     *
     * @var string
     */
    private string $mailerEmail;

    /**
     * mailer default name
     *
     * @var string
     */
    private string $mailerName;

    public function __construct(
        TransportInterface $mailer,
        string $mailProviderDefaultEmail,
        string $mailProviderDefaultName,
    ) {
        $this->smtpMailer = $mailer;
        $this->mailerEmail = $mailProviderDefaultEmail;
        $this->mailerName = $mailProviderDefaultName;
    }

    /**
     * @inheritDoc
     */
    public function send(
        string $toMail,
        string $subject,
        string $content,
        ?string $senderName = null,
        ?string $senderEmail = null,
        string $type = 'text/html',
    ): Result
    {
        $email = (new Email())
            ->to($toMail)
            ->subject($subject)
            ->html($content);

        if ($senderName && $senderEmail) {
            $address = new Address($senderEmail, $senderName);
        } else {
            $address = new Address($this->mailerEmail, $this->mailerName);
        }
        $email->from($address);

        $result = new Result();
        try {
            $this->smtpMailer->send($email);
            $result->setStatus($result::STATUS_SUCCESS);
            $result->setMessage('Successfully sent mail');
        } catch (TransportExceptionInterface $e) {
            $result->setMessage(self::PROVIDER_NAME . ': ' . $e->getMessage());
        }

        return $result;
    }

}