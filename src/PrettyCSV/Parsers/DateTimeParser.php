<?php

declare(strict_types=1);

namespace PrettyCSV\Parsers;

use PrettyCSV\Interfaces\TypeParserInterface;
use DateTime;

class DateTimeParser implements TypeParserInterface {

    /**
     * @var string
     */
    private string $format;

    /**
     * @var bool
     */
    private $is_strict;

    /**
     * @param string $format
     * @param bool $is_strict
     */
    public function __construct(
        string $format,
        bool $is_strict = false
    ){
        $this->format = $format;
        $this->is_strict = $is_strict;
    }

    /**
     * @param string $data
     * @return DateTime| null
     * @throws ParserException
     */
     public function parse(string $data): ?DateTime {
         $return = DateTime::createFromFormat($this->format, $data);
         if($return === false) {
             if ($this->is_strict) {
                 throw new ParserException("Cannot parse '{$data}' as DateTime.");
             } else {
                 $return = null;
             }
         }
         return $return;
     }

}