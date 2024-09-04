<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Type\TypeInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class FlexForm
 * @package Devsk\DsNotifier\Domain\Model\Notification
 */
class FlexForm implements TypeInterface
{

    protected array $data = [];

    protected ?FlexFormService $flexFormService = null;
    protected ?FlexFormTools $flexFormTools = null;

    public function __construct(string $flexFormString)
    {
        $this->flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
        $this->flexFormTools = GeneralUtility::makeInstance(FlexFormTools::class);
        $this->data = $this->flexFormService->convertFlexFormContentToArray($flexFormString);
    }

    public function __call($method, $arguments): mixed
    {
        if (str_starts_with($method, 'get')
            && strlen($method) > 3
        ) {
            $propertyName = lcfirst(substr($method, 3));

            return $this->$propertyName;

        }

        return null;
    }

    public function __get($property): mixed
    {
        return array_key_exists($property, $this->data) ? $this->data[$property] : null;
    }

    public function __toString()
    {
        return $this->flexFormTools->flexArray2Xml($this->data);
    }
}
