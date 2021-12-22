<?php

declare(strict_types=1);

namespace PrettyCSV\Parsers;

use PrettyCSV\Interfaces\TypeParserInterface;
use DateTime;

class SerializeParser implements TypeParserInterface {

    /**
     * @var bool
     */
    private $is_strict;

    /**
     * @param bool $is_strict
     */
    public function __construct(
        bool $is_strict = false
    ){
        $this->is_strict = $is_strict;
    }

    /**
     * @param string $data
     * @return mixed
     * @throws ParserException
     */
     public function parse(string $data): mixed {
         $return = unserialize($data);
         if($return === false) {
             if ($this->is_strict) {
                 throw new ParserException("Cannot parse '{$data}' as Serialization.");
             } else {
                 $return = null;
             }
         }
         return $return;
     }

}