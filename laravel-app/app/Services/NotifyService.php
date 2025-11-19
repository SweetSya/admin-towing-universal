<?php

namespace App\Services;

class NotifyService
{
    public function notify(string $type, string $message): array
    {
        return [
            'notify' => [
                'type' => $type,
                'message' => $message,
            ],
        ];
    }
}