<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use Countable;
use Devsk\DsNotifier\Domain\Model\Notification\Attachment;
use Traversable;

/**
 * Class AttachmentCollection
 * @package Devsk\DsNotifier\Domain\Model\Notification\Email
 */
class AttachmentCollection implements  \IteratorAggregate, Countable
{

    /**
     * @var Attachment[]
     */
    protected array $attachments = [];

    public function __construct(Attachment ...$attachments)
    {
        foreach ($attachments as $attachment) {
            $this->attachments[] = $attachment;
        }
    }

    public function getIterator(): Traversable
    {
        return yield from $this->attachments;
    }

    public function add(Attachment $attachment)
    {
        $this->attachments[] = $attachment;
    }

    public function count(): int
    {
        return count($this->attachments);
    }

    public function isEmpty(): bool
    {
        return $this->attachments === [];
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }
}
