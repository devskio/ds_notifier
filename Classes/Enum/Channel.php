<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Enum;

enum Channel: string
{
    case EMAIL = 'email';

    case SLACK = 'slack';
}
