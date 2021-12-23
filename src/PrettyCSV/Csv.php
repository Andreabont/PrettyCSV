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
     * @var int
     */
    protected int $dataStartPoint;

    /**
     * @var HeaderColumnInterface[]
     */
    protected array $headerList = [];

    /**
     * @var bool
     */
    protected bool $hasHeader = false;

    /**
     * @param string $file
     * @param array|bool $header
     * @param string $separator
     * @param string $enclosure
     * @param string $escape
     * @param int $length
     * @throws ParserException
     */
    public function __construct(
        string     $file,
        array|bool $header = false,
        string     $separator = ',',
        string     $enclosure = '"',
        string     $escape = '\\',
        int        $length = 1000
    ) {

        $this->fp = fopen($file, 'r');
        $this->separator = $separator;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->length = $length;
        $this->hasHeader = is_array($header) || $header;

        if($this->hasHeader) {

            $this->next();

            if(!$this->valid()) {
                throw new ParserException("Header non found", $this->currentRow);
            }

            $expectedColumns = [];

            if(is_array($header)) {
                /** @var HeaderColumnInterface $headerColumn */
                foreach ($header as $headerColumn) {
                    if(!($headerColumn instanceof HeaderColumnInterface)) continue;
                    $this->headerList[$headerColumn->getName()] = $headerColumn;
                    if($headerColumn->getIsRequired()) $expectedColumns[] = $headerColumn->getName();
                }
            }

            $csvColumnIndex = 0;
            foreach (str_getcsv($this->currentLine, $this->separator, $this->enclosure, $this->escape) as $csvColumnName) {
                if(!array_key_exists($csvColumnName, $this->headerList)) {
                    $this->headerList[$csvColumnName] = new HeaderColumn($csvColumnName);
                }
                $this->headerList[$csvColumnName]->setIndex($csvColumnIndex);
                if(in_array($csvColumnName, $expectedColumns)) {
                    unset($expectedColumns[array_search($csvColumnName, $expectedColumns)]);
                }
                $csvColumnIndex++;
            }

            if(count($expectedColumns) > 0) {
                throw new ParserException("Expected columns: " . implode(', ', $expectedColumns), $this->currentRow);
            }

        }

        $this->dataStartPoint = ftell($this->fp);

    }

    /**
     * Destructor
     */
    function __destruct() {
        fclose($this->fp);
    }

    /**
     * @return mixed
     */
    public function current() : mixed {
        $line = str_getcsv($this->currentLine, $this->separator, $this->enclosure, $this->escape);
        if(!$this->hasHeader) return $line;
        return new CsvLine($line, $this->currentRow, $this->headerList);
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
    public function next() : void {
        $this->currentLine = fgets($this->fp, $this->length);
        $this->currentRow++;
    }

    /**
     * @return void
     */
    public function rewind() : void {
        fseek($this->fp, $this->dataStartPoint, SEEK_SET);
        $this->currentRow = 0;
        $this->next();
    }

    /**
     * @return bool
     */
    public function valid() : bool {
        return $this->currentLine !== false;
    }

}