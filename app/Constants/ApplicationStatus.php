<?php

namespace App\Constants;


class ApplicationStatus
{

    public const FORWARD = 1;

    public const APPROVE = 2;

    public const WAITING = 3;

    public const REJECTED = 4;


    public const ALL = [
        self::FORWARD => 'Forward',
        self::APPROVE => 'Approve',
        self::WAITING => 'Waiting',
        self::REJECTED => 'Rejected',
    ];


}
