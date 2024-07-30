<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Exception;

use Exception;

/**
 * Class EventCancelledException
 * @package Devsk\DsNotifier\Exception
 */
class EventCancelledException extends Exception
{
    protected $message = 'Event was cancelled';
    protected $code = 1721219875;
}
