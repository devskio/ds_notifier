<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use Devsk\DsNotifier\Domain\Model\Notification;

/**
 * Class Email
 * @package Devsk\DsNotifier\Domain\Model
 */
class Email extends Notification
{

    protected ?string $to = null;
    protected ?string $cc = null;
    protected ?string $bcc = null;

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function getCc(): ?string
    {
        return $this->cc;
    }

    public function getBcc(): ?string
    {
        return $this->bcc;
    }
}
