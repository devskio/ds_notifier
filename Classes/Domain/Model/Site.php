<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model;

/**
 * Class Site
 * @package Devsk\DsNotifier\Domain\Model
 */
class Site extends AbstractEntity
{

    protected string $title = '';

    static function tableName(): string
    {
        return 'pages';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

}
