<?php

namespace App\Service\Notification\Provider\Result;

class Result
{
    public const STATUS_SUCCESS = 1;
    public const STATUS_ERROR = 2;

    private const statusData = [
        self::STATUS_SUCCESS => [
            'name' => 'success',
            'code' => self::STATUS_SUCCESS,
            'class' => 'success',
        ],
        self::STATUS_ERROR => [
            'name' => 'error',
            'code' => self::STATUS_ERROR,
            'class' => 'danger',
        ],
    ];

    private int $status = self::STATUS_ERROR;
    private string $message = 'message';

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * get all statuses
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return self::statusData ?? [];
    }

    /**
     * get statuses key value
     *
     * @return array
     */
    public static function getStatusesKeyValue(): array
    {
        $data = [];
        foreach (self::statusData as $key => $value) {
            $data[$key] = $value['name'];
        }

        return $data;
    }
}