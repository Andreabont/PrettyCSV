<?php

declare(strict_types=1);

namespace PrettyCSV\Parsers;

use Exception;

class ParserException extends Exception {

    /**
     * @var int|null
     */
    protected ?int $csvLine;

    /**
     * @param string $message
     * @param int|null $csvLine
     */
    public function __construct(string $message, ?int $csvLine = null) {
        parent::__construct($message);
        $this->csvLine = $csvLine;
    }

    /**
     * @return int|null
     */
    public function getCsvLine() : ?int {
        return $this->csvLine;
    }

    /**
     * @param int $csv_line
     * @return $this
     */
    public function setCsvLine(int $csv_line) : ParserException {
        $this->csvLine = $csv_line;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() {
        return "On line {$this->csvLine}: " . parent::__toString();
    }

}