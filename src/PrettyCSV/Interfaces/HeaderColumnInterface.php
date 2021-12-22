<?php

declare(strict_types=1);

namespace PrettyCSV\Interfaces;

interface HeaderColumnInterface {

    /**
     * @param string $name
     * @return HeaderColumnInterface
     */
    public function setName(string $name) : HeaderColumnInterface;

    /**
     * @return string|null
     */
    public function getName() : ?string;

    /**
     * @param bool $is_required
     * @return HeaderColumnInterface
     */
    public function setIsRequired(bool $is_required) : HeaderColumnInterface;

    /**
     * @return bool
     */
    public function getIsRequired() : bool;

    /**
     * @param int $index
     * @return HeaderColumnInterface
     */
    public function setIndex(int $index) : HeaderColumnInterface;

    /**
     * @return int|null
     */
    public function getIndex() : ?int;

    /**
     * @param TypeParserInterface $parser
     * @return HeaderColumnInterface
     */
    public function setParser(TypeParserInterface $parser) : HeaderColumnInterface;

    /**
     * @return TypeParserInterface|null
     */
    public function getParser() : ?TypeParserInterface;

}