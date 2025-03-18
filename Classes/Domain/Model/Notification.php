<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

use Devsk\DsNotifier\Domain\Model\Notification\FlexibleConfiguration;
use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class Notification
 * @package Devsk\DsNotifier\Domain\Model
 */
abstract class Notification extends AbstractEntity implements NotificationInterface
{

    protected ?string $title = null;

    protected ?string $channel = null;

    protected ?string $event = null;

    protected ?string $subject = null;

    protected ?string $body = null;

    protected string $layout = '';

    protected ?FlexibleConfiguration $configuration = null;

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
    public function getCompiledSubject(array $variables = []): ?string
    {
        return $this->compileTemplateString($this->subject, $variables);
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function getConfiguration(): ?FlexibleConfiguration
    {
        return $this->configuration;
    }

    protected function compileTemplateString(?string $templateString, array $variables = [], bool $escape = true): string
    {
        if ((new Typo3Version())->getMajorVersion() < 13) {
            // @extensionScannerIgnoreLine
            // @todo: Remove this once we drop support for TYPO3 v12
            $view = GeneralUtility::makeInstance(StandaloneView::class);
        } else {
            $view = GeneralUtility::makeInstance(ViewFactoryInterface::class)
                ->create(new ViewFactoryData());
        }

        $escaping = $escape ? null : '{escaping off}';
        return (string)$view->assignMultiple($variables)
            ->getRenderingContext()
            ->getTemplateParser()
            ->parse($escaping . $templateString)
            ->render($view->getRenderingContext());
    }
}
