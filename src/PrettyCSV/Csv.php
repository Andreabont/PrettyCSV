<?php

declare(strict_types=1);

namespace PrettyCSV;

use Iterator;
use PrettyCSV\Interfaces\HeaderColumnInterface;
use PrettyCSV\Parsers\ParserException;

class Csv implements Iterator {

    /**
     * @var resource|false
     */
    protected $fp;

    /**
     * @var int
     */
    protected int $length;

    /**
     * @var string
     */
    protected string $separator;

    /**
     * @var string
     */
    protected string $enclosure;

    /**
     * @var string
     */
    protected string $escape;

    /**
     * @var string|false
     */
    protected string|false $currentLine;

    /**
     * @var int
     */
    protected int $currentRow = 0;

    /**
     * @var HeaderColumnInterface[]
     */
    protected array $headers = [];

    /**
     * @var bool
     */
    protected bool $hasHeader = false;

    /**
     * @param string $file
     * @param array|null $headers
     * @param string $separator
     * @param string $enclosure
     * @param string $escape
     * @param int $length
     * @throws ParserException
     */
    public function __construct(
        string $file,
        array $headers = null,
        string $separator = ',',
        string $enclosure = '"',
        string $escape = '\\',
        int $length = 0
    ) {

        $this->fp = fopen($file, 'r');
        $this->separator = $separator;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->length = $length;

        $this->next();

        if(!is_null($headers)) {

            $this->hasHeader = true;
            $expectedColumns = [];

            /** @var HeaderColumnInterface $header */
            foreach ($headers as $header) {
                $this->headers[$header->getName()] = $header;
                if($header->getIsRequired()) $expectedColumns[] = $header->getName();
            }

            $line = str_getcsv($this->currentLine, $this->separator, $this->enclosure, $this->escape);
            $csvColumnIndex = 0;

            foreach ($line as $csvColumnName) {
                if(!array_key_exists($csvColumnName, $this->headers)) {
                    $this->headers[$csvColumnName] = (new HeaderColumn())->setName($csvColumnName);
                }
                $this->headers[$csvColumnName]->setIndex($csvColumnIndex);
                unset($expectedColumns[$csvColumnName]);
                $csvColumnIndex++;
            }

            if(count($expectedColumns) > 0) {
                throw new ParserException("Expected columns: " . implode(', ', $expectedColumns), $this->currentRow);
            }

            $this->next();

        }

    }

    /**
     * @return array|CsvLine
     */
    public function current() {
        $line = str_getcsv($this->currentLine, $this->separator, $this->enclosure, $this->escape);
        if(!$this->hasHeader) return $line;
        return new CsvLine($line, $this->currentRow, $this->headers);
    }

    /**
     * @return int
     */
    public function key() : int {
        return $this->currentRow;
    }

    /**
     * @return void
     */
    public function next() {
        $this->currentLine = fgets($this->fp, $this->length);
        $this->currentRow++;
    }

    /**
     * @return void
     */
    public function rewind() {
        rewind($this->fp);
        $this->currentRow = 1;
    }

    /**
     * @return bool
     */
    public function valid() : bool {
        return $this->currentLine !== false;
    }

}