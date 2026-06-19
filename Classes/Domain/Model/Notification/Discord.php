<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\Domain\Model\Notification\Discord\Recipients;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

/**
 * Class Discord
 * @package Devsk\DsNotifier\Domain\Model
 */
class Discord extends Notification
{
    protected ?Recipients $discordChannels = null;

    public function send(EventInterface $event): void
    {
        foreach ($this->discordChannels->getRecipients() as $recipient) {
            $this->sendToRecipient($recipient, $event);
        }
    }
    public function sendToRecipient(array $recipient, EventInterface $event): void
    {
        if (!empty($recipient['discord_webhook'])) {
            $body = $this->replaceMarkers($this->getBody(),  $event->getMarkerProperties());
            // Discord webhook payload structure
            $payload = [
                'content' => $body,
                'username' => $this->getTitle() ?? 'Notification',
            ];

            $additionOptions = [
                'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
                'body' => json_encode($payload),
            ];
            $request = GeneralUtility::makeInstance(RequestFactory::class);
            $request->request(
                $recipient['discord_webhook'],
                'POST',
                $additionOptions);
        }
    }
}
