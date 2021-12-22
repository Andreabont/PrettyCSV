<?php

declare(strict_types=1);

namespace PrettyCSV\Parsers;

use PrettyCSV\Interfaces\TypeParserInterface;
use DateTime;

class DateTimeParser implements TypeParserInterface {

    /**
     * @var string
     */
    private string $separator;

    /**
     * @var bool
     */
    private $is_strict;

    /**
     * @param string $separator
     * @param bool $is_strict
     */
    public function __construct(
        string $separator,
        bool $is_strict = false
    ){
        $this->separator = $separator;
        $this->is_strict = $is_strict;
    }

    /**
     * @param string $data
     * @return array|null
     * @throws ParserException
     */
     public function parse(string $data): ?array {
         $return = explode($this->separator, $data);
         if($return === false) {
             if ($this->is_strict) {
                 throw new ParserException("Cannot parse '{$data}' as List.");
             } else {
                 $return = null;
             }
         }
         return $return;
     }

}