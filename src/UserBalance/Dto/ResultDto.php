<?php

namespace app\src\UserBalance\Dto;

final class ResultDto
{
    private string $message;
    private ?int $status;

    /**
     * @param string $message
     * @param int|null $status
     */
    public function __construct(string $message, ?int $status = null)
    {
        $this->message = $message;
        $this->status = $status ?? null;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }
}