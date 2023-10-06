<?php

namespace App\Service\Notification;

use App\Entity\Notification\NotificationLog;
use App\Entity\User\User;
use App\Repository\Notification\NotificationLogRepository;
use App\Service\Notification\Provider\Collection\ProviderCollection;
use App\Service\Notification\Provider\Mail\MailProviderAbstract;
use App\Service\Notification\Provider\ProviderAbstract;
use App\Service\Notification\Provider\Result\Result;

class NotificationManager
{
    private string $defaultMailProviderName;

    private ProviderCollection $providerCollection;

    private NotificationLogRepository $notificationLogRepository;

    public function __construct(
        ProviderCollection $providerCollection,
        NotificationLogRepository $notificationLogRepository,
        string $defaultMailProviderName,
    ) {
        $this->providerCollection = $providerCollection;
        $this->notificationLogRepository = $notificationLogRepository;

        $this->defaultMailProviderName = $defaultMailProviderName;
    }

    /**
     * send email
     *
     * @param User $user
     * @param string $subject
     * @param string $content
     * @param string|null $providerName (default: null)
     *
     * @return Result
     */
    public function sendEmailToUser(User $user, string $subject, string $content, ?string $providerName = null): Result
    {
        if ($providerName === null) {
            $providerName = $this->defaultMailProviderName;
        }

        /** @var MailProviderAbstract $provider */
        $provider = $this->providerCollection->getProviderByName($providerName);
        if ($provider === null) {
            $result = new Result();
            $result->setMessage('Invalid provider name: ' . $providerName);
            $this->createLog(null, $user, $user->getEmail(), $subject, $content, $result);
            return $result;
        }

        $result = $provider->send($user->getEmail(), $subject, $content);
        $this->createLog($provider, $user, $user->getEmail(), $subject, $content, $result);

        return $result;
    }

    /**
     * create notification log
     *
     * @param ProviderAbstract|null $provider
     * @param User|null $user
     * @param string $toMail
     * @param string $subject
     * @param string $content
     * @param Result $result
     *
     * @return void
     */
    private function createLog(?ProviderAbstract $provider, ?User $user, string $toMail, string $subject, string $content, Result $result): void
    {
        $notificationLog = new NotificationLog();
        $notificationLog->setProvider($provider ? $provider->getName() : 'undefined');

        $notificationLog->setSubject($subject);
        $notificationLog->setContent($content);
        if ($user instanceof User) {
            $notificationLog->setUser($user);
        }

        $notificationLog->setDate(new \DateTime());
        $notificationLog->setStatus($result->getStatus());
        $notificationLog->setMessage(
            'toMail: ' . $toMail .
            ' - ' . $result->getMessage()
        );

        $this->notificationLogRepository->save($notificationLog, true);
    }
}