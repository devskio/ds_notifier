<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification\Email;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Exception\InvalidArgumentException;
use Traversable;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Type\TypeInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

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
            if (MathUtility::canBeInterpretedAsInteger($recipient)) {
                if ($address = $this->getAddressByUid($recipient)) {
                    $this->recipients[] = $address;
                }
            } elseif (str_contains($recipient, '@')) {
                $this->recipients[] = Address::create($recipient);
            } else {
                $this->recipients[] = $recipient;
            }
        }
    }

    public function __toString()
    {
        return implode(',', array_map(fn (Address|string $address) =>
            $address instanceof Address ? $address->toString() : (string)$address,
            $this->recipients
        ));
    }

    public function getIterator(): Traversable
    {
        return yield from $this->recipients;
    }

    protected function getAddressByUid($uid): ?Address
    {
        $recipient = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_dsnotifier_domain_model_recipient')
            ->select(
                ['email', 'name'],
                'tx_dsnotifier_domain_model_recipient',
                ['uid' => $uid]
            )->fetchAssociative();

        return $recipient ? new Address($recipient['email'], $recipient['name']) : null;
    }

    public function addRecipients(Address|string ...$addresses): self
    {
        foreach ($addresses as $address) {
            $this->recipients = array_merge($this->recipients, $this->parseAddress($address));
        }
        return $this;
    }

    public function updateRecipient(?Address $address, int $key): self
    {
        if ($address) {
            $this->recipients[$key] = $address;
        }

        return $this;
    }

    public function unsetRecipient(int $key): self
    {
        if (array_key_exists($key, $this->recipients)) {
            unset($this->recipients[$key]);
        }

        return $this;
    }

    /**
     *
     * @param Address|array|string $address
     * @return Address[]
     * @throws InvalidArgumentException
     */
    protected function parseAddress(Address|array|string $address): array
    {
        if ($address instanceof Address) {
            return [$address];
        }
        if (is_array($address)) {
            return Address::createArray($address);
        }

        return Address::createArray(preg_split('/[,;]/', $address));
    }
}
