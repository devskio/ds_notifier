<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use Devsk\DsNotifier\Domain\Model\Notification;
use Devsk\DsNotifier\Event\EventInterface;
use Devsk\DsNotifier\Domain\Model\Notification\Slack\Recipients;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use TYPO3\CMS\Core\Http\RequestFactory;

/**
 * Class Slack
 * @package Devsk\DsNotifier\Domain\Model
 */
class Slack extends Notification
{
    protected ?Recipients $slackChannels = null;

    public function send(EventInterface $event): void
    {
        foreach ($this->slackChannels->getRecipients() as $recipient) {
            $this->sendToRecipient($recipient, $event);
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendToRecipient(array $recipient, EventInterface $event): void
    {
        if (isset($recipient['slack_channel']) && !empty($recipient['slack_channel'])) {
            $body = $this->getBody();
            $markers = $event->getMarkerProperties();
            foreach ($markers as $key => $value) {
                $body = str_replace('{'.$key.'}', strval($value), $body);
            }
            $additionOptions = [
                'headers' => ['Content-Type' => 'application/json; charset=utf-8'],
                'body' => json_encode(['text' => $body]),
            ];
            $request = GeneralUtility::makeInstance(RequestFactory::class);
            $request->request(
                $recipient['slack_channel'],
                'POST',
                $additionOptions);
        }
    }
}
