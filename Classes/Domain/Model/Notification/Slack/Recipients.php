<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification\Slack;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Exception\InvalidArgumentException;
use Traversable;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Type\TypeInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use function Devsk\DsNotifier\Domain\Model\Notification\Email\str_contains;

/**
 * Class Recipients
 * @package Devsk\DsNotifier\Domain\Model\Notification\Email
 */
class Recipients implements TypeInterface, \IteratorAggregate
{

    protected array $recipients = [];

    public function __construct(string ...$recipients)
    {
        if (func_num_args() === 1) {
            $recipients = GeneralUtility::trimExplode(',', $recipients[0], true);
        }

        foreach ($recipients as $recipient) {
            if (MathUtility::canBeInterpretedAsInteger($recipient)) {
                if ($channel = $this->getChannelByUid($recipient)) {
                    $this->recipients[] = $channel;
                }
            }
        }
    }

    public function getIterator(): Traversable
    {
        return yield from $this->recipients;
    }

    public function __toString()
    {
        return json_encode($this->recipients);
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    protected function getChannelByUid($uid): array
    {
        $recipient = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_dsnotifier_domain_model_recipient')
            ->select(
                ['workspace', 'slack_channel'],
                'tx_dsnotifier_domain_model_recipient',
                ['uid' => $uid]
            )->fetchAssociative();

        $webhook = $this->getWorkspaceByUid($recipient['workspace']);
        if($webhook && $recipient['slack_channel']){
            return ['webhook' => $webhook, 'slack_channel' => $recipient['slack_channel']];
        }

        return [];
    }

    protected function getWorkspaceByUid($uid): ?string
    {
        $workspace = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_dsnotifier_domain_model_slack_workspace')
            ->select(
                ['webhook'],
                'tx_dsnotifier_domain_model_slack_workspace',
                ['uid' => $uid]
            )->fetchAssociative();

        return $workspace['webhook'] ?? null;
    }

}
