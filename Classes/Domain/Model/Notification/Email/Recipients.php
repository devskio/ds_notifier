<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification\Email;

use Symfony\Component\Mime\Address;
use Traversable;
use TYPO3\CMS\Core\Type\TypeInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class Recipients
 * @package Devsk\DsNotifier\Domain\Model\Notification\Email
 */
class Recipients implements TypeInterface, \IteratorAggregate
{

    /**
     * @var Address[]
     */
    protected array $recipients = [];

    public function __construct(string ...$recipients)
    {
        if (func_num_args() === 1) {
            $recipients = GeneralUtility::trimExplode(',', $recipients[0], true);
        }

        foreach ($recipients as $recipient) {
            $this->recipients[] = Address::create($recipient);
        }
    }

    public function __toString()
    {
        return implode(',', array_map(fn (Address $address) => $address->toString(), $this->recipients));
    }

    public function getIterator(): Traversable
    {
        return yield from $this->recipients;
    }
}
