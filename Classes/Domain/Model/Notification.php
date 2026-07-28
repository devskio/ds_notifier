<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

use Devsk\DsNotifier\Domain\Model\Notification\FlexibleConfiguration;
use Devsk\DsNotifier\Event\EventInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

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
        $view = GeneralUtility::makeInstance(ViewFactoryInterface::class)
            ->create(new ViewFactoryData());

        $escaping = $escape ? null : '{escaping off}';
        return (string)$view->assignMultiple($variables)
            ->getRenderingContext()
            ->getTemplateParser()
            ->parse($escaping . $templateString)
            ->render($view->getRenderingContext());
    }

    /**
     * Replace markers in body using Fluid template engine.
     * Supports {key}, {key.subkey} dot-notation natively.
     * Array leaf values are converted to comma-separated strings so Fluid
     * can render them without throwing "Cannot cast an array to string".
     *
     * @param string $body
     * @param array $markers
     * @return string
     */
    protected function replaceMarkers(string $body, array $markers): string
    {
        return $this->compileTemplateString($body, $this->flattenMarkersForFluid($markers), false);
    }

    /**
     * Recursively walk markers and convert any leaf-level array values
     * (e.g. multi-checkbox form values) to comma-separated strings so
     * Fluid can render them as plain text without throwing a cast error.
     *
     * @param array $markers
     * @return array
     */
    protected function flattenMarkersForFluid(array $markers): array
    {
        foreach ($markers as $key => $value) {
            if (is_array($value)) {
                // Check if all children are scalar — if so, join them; otherwise recurse
                $hasNestedArrays = array_filter($value, 'is_array');
                if ($hasNestedArrays) {
                    $markers[$key] = $this->flattenMarkersForFluid($value);
                } else {
                    $markers[$key] = implode(', ', array_map('strval', array_filter($value, fn($v) => !is_object($v))));
                }
            }
        }
        return $markers;
    }
}
