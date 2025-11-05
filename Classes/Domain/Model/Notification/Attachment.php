<?php
declare(strict_types=1);

namespace Devsk\DsNotifier\Domain\Model\Notification;

use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class Attachment
{

    public function __construct(
        protected string $content,
        protected string $name,
        protected ?string $contentType = null
    )
    {}

    public static function fromFileReference(FileReference $file): self
    {
        $originalResource = $file->getOriginalResource();
        return new self(
            $originalResource->getContents(),
            $originalResource->getName(),
            $originalResource->getMimeType()
        );
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }
}
