<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Site
 * @package Devsk\DsNotifier\Domain\Model
 */
class Site extends AbstractEntity
{

    protected string $title = '';

    public function getTitle(): string
    {
        return $this->title;
    }

}
