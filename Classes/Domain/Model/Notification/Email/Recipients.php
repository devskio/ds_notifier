<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification\Email;

use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Type\TypeInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use function array_map;
use function implode;

/**
 * Class Recipients
 * @package Devsk\DsNotifier\Domain\Model\Notification\Email
 */
class Recipients implements TypeInterface
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
}
