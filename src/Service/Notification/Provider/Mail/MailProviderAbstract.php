<?php

namespace App\Service\Notification\Provider\Mail;

use App\Service\Notification\Provider\ProviderAbstract;
use App\Service\Notification\Provider\Result\Result;

abstract class MailProviderAbstract extends ProviderAbstract
{
    /**
     * send mail
     *
     * @param string $toMail
     * @param string $subject
     * @param string $content
     * @param string|null $senderName (default: null)
     * @param string|null $senderEmail (default: null)
     * @param string $type (default: text/html)
     *
     * @return Result
     */
    abstract public function send(
        string $toMail,
        string $subject,
        string $content,
        ?string $senderName = null,
        ?string $senderEmail = null,
        string $type = 'text/html',
    ): Result;
}