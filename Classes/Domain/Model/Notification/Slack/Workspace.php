<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification\Slack;

/**
 * Class Recipients
 * @package Devsk\DsNotifier\Domain\Model\Notification\Email
 */
class Workspace
{
    /**
     * @var string
     */
    protected string $webhook = '';

    /**
     * @return string
     */
    public function getWebhook()
    {
        return $this->webhook;
    }

    /**
     * @param string $webhook
     */
    public function setWebhook($webhook)
    {
        $this->webhook = $webhook;
    }

}
