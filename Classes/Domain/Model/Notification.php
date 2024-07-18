<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Class Notification
 * @package Devsk\DsNotifier\Domain\Model
 */
abstract class Notification extends AbstractEntity
{

    protected ?string $title = null;

    protected ?string $channel = null;

    protected ?string $event = null;

    protected ?string $subject = null;

    protected ?string $body = null;

    static function tableName(): string
    {
        return 'tx_dsnotifier_domain_model_notification';
    }

    abstract public function send(EventInterface $event): void;

    /**
     * @var ObjectStorage<Site>
     */
    protected ?ObjectStorage $sites = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function getSites(): ?ObjectStorage
    {
        return $this->sites;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }
}
