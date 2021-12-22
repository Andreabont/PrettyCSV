<?php

declare(strict_types=1);

namespace PrettyCSV;

use PrettyCSV\Interfaces\HeaderColumnInterface;
use PrettyCSV\Interfaces\TypeParserInterface;

class HeaderColumn implements HeaderColumnInterface {

    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @var bool
     */
    private bool $is_required = false;

    /**
     * @var int|null
     */
    private ?int $index = null;

    /**
     * @var TypeParserInterface|null
     */
    private ?TypeParserInterface $parser;

    /**
     * @inheritDoc
     */
    public function setName(string $name): HeaderColumnInterface {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setIsRequired(bool $is_required): HeaderColumnInterface {
        $this->is_required = $is_required;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIsRequired(): bool {
        return $this->is_required;
    }

    /**
     * @inheritDoc
     */
    public function setIndex(int $index): HeaderColumnInterface {
        $this->index = $index;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): ?int {
        return $this->index;
    }

    /**
     * @inheritDoc
     */
    public function setParser(TypeParserInterface $parser): HeaderColumnInterface {
        $this->parser = $parser;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParser(): ?TypeParserInterface {
        return $this->parser;
    }

}