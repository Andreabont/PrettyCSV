<?php

declare(strict_types=1);

namespace PrettyCSV;

use PrettyCSV\Interfaces\HeaderColumnInterface;
use PrettyCSV\Interfaces\TypeParserInterface;
use PrettyCSV\Parsers\ParserException;
use ArrayObject;
use ArrayIterator;
use Iterator;

class CsvLine extends ArrayObject {

    /**
     * @var array
     */
    protected array $currentLine;

    /**
     * @var int
     */
    protected int $currentRow;

    /**
     * @var HeaderColumnInterface[]
     */
    protected array $headerList = [];

    /**
     * @var array
     */
    protected array $parsedDataCache = [];

    /**
     * @param array $currentLine
     * @param int $currentRow
     * @param array $headerList
     */
    public function __construct(
        array $currentLine,
        int $currentRow,
        array $headerList
    ){
        $this->currentLine = $currentLine;
        $this->currentRow = $currentRow;
        $this->headerList = $headerList;
    }

    /**
     * @param string|int $name
     * @return bool
     */
    private function existsField(string|int $name): bool {
        return array_key_exists($name, $this->headerList) && !is_null($this->headerList[$name]->getIndex());
    }

    /**
     * @param string|int $name
     * @return mixed
     * @throws ParserException
     */
    private function getField(string|int $name): mixed {
        if(!$this->existsField($name)) {
            throw new ParserException("Field '{$name}' not found", $this->currentRow);
        }
        $index = $this->headerList[$name]->getIndex();
        /** @var TypeParserInterface $parser */
        $parser = $this->headerList[$name]->getParser();
        if(!array_key_exists($name, $this->parsedDataCache)) {
            if(!array_key_exists($index, $this->currentLine)) return null;
            $data = $this->currentLine[$index];
            try {
                $this->parsedDataCache[$name] = (!is_null($parser))? $parser->parse($data) : $data;
            } catch (ParserException $e) {
                throw $e->setCsvLine($this->getRowNumber());
            }
        }
        return $this->parsedDataCache[$name];
    }

    /**
     * @return int
     */
    public function getRowNumber() : int {
        return $this->currentRow;
    }

    /**
     * @return int
     */
    public function count() : int {
        return count($this->currentLine);
    }

    /**
     * @param $key
     * @return mixed
     * @throws ParserException
     */
    public function offsetGet($key) : mixed {
        return $this->getField($key);
    }

    /**
     * @param $key
     * @return bool
     */
    public function offsetExists($key): bool {
        return $this->existsField($key);
    }

    /**
     * @return array
     * @throws ParserException
     */
    public function getArrayCopy(): array {
        $result = [];
        foreach ($this->headerList as $name => $header) {
            if (is_null($header->getIndex())) {
                continue;
            }
            $result[$name] = $this->getField($name);
        }
        return $result;
    }

    /**
     * @return Iterator
     * @throws ParserException
     */
    public function getIterator() : Iterator {
        return new ArrayIterator($this->getArrayCopy(), $this->getFlags());
    }

    /**
     * @return array
     * @throws ParserException
     */
    public function __debugInfo() : array {
        return $this->getArrayCopy();
    }

}