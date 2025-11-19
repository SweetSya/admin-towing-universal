<?php

namespace App\Services;

class NotifyService
{
    /**
     * Create a success notification
     *
     * @param string $message
     * @return array
     */
    public static function success(string $message): array
    {
        return [
            'notify' => [
                'type' => 'success',
                'message' => $message
            ]
        ];
    }

    /**
     * Create an error notification
     *
     * @param string $message
     * @return array
     */
    public static function error(string $message): array
    {
        return [
            'notify' => [
                'type' => 'error',
                'message' => $message
            ]
        ];
    }

    /**
     * Create a warning notification
     *
     * @param string $message
     * @return array
     */
    public static function warning(string $message): array
    {
        return [
            'notify' => [
                'type' => 'warning',
                'message' => $message
            ]
        ];
    }

    /**
     * Create an info notification
     *
     * @param string $message
     * @return array
     */
    public static function info(string $message): array
    {
        return [
            'notify' => [
                'type' => 'info',
                'message' => $message
            ]
        ];
    }

    /**
     * Create a custom notification
     *
     * @param string $type
     * @param string $message
     * @return array
     */
    public static function custom(string $type, string $message): array
    {
        return [
            'notify' => [
                'type' => $type,
                'message' => $message
            ]
        ];
    }

    /**
     * Flash a success notification to session
     *
     * @param string $message
     * @return void
     */
    public static function flashSuccess(string $message): void
    {
        session()->flash('notify', [
            'type' => 'success',
            'message' => $message
        ]);
    }

    /**
     * Flash an error notification to session
     *
     * @param string $message
     * @return void
     */
    public static function flashError(string $message): void
    {
        session()->flash('notify', [
            'type' => 'error',
            'message' => $message
        ]);
    }

    /**
     * Flash a warning notification to session
     *
     * @param string $message
     * @return void
     */
    public static function flashWarning(string $message): void
    {
        session()->flash('notify', [
            'type' => 'warning',
            'message' => $message
        ]);
    }

    /**
     * Flash an info notification to session
     *
     * @param string $message
     * @return void
     */
    public static function flashInfo(string $message): void
    {
        session()->flash('notify', [
            'type' => 'info',
            'message' => $message
        ]);
    }
}