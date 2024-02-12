<?php

namespace App\Constants;

class Status
{
    public const OPERATION_SUCCESSFUL = 200;
    public const VALIDATION_FAILED = 400;
    public const AUTHENTICATION_FAILED = 401;
    public const TOO_MANY_ATTEMPTS = 402;
    public const PERMISSION_DENIED = 403;
    public const NOT_FOUND = 404;
    public const ROUTE_NOT_FOUND = 410;

    public static function getMessage($code)
    {
        $messages = [
            self::OPERATION_SUCCESSFUL => "Operation successful",
            self::VALIDATION_FAILED => "Validation failed",
            self::AUTHENTICATION_FAILED => "Authentication failed",
            self::TOO_MANY_ATTEMPTS => "Too many requests",
            self::PERMISSION_DENIED => "Permission denied",
            self::NOT_FOUND => "Not found",
            self::ROUTE_NOT_FOUND => "The selected route is invalid",
        ];

        return $messages[$code];
    }
}
