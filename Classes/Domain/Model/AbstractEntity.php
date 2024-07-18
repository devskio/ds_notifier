<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

/**
 * Class AbstractEntity
 * @package Devsk\DsNotifier\Domain\Model
 */
abstract class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    abstract static function tableName(): string;
}
