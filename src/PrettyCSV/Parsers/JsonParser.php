<?php

declare(strict_types=1);

namespace PrettyCSV\Parsers;

use PrettyCSV\Interfaces\TypeParserInterface;
use DateTime;

class JsonParser implements TypeParserInterface {

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
     * @return array|null
     * @throws ParserException
     */
     public function parse(string $data): ?array {
         $return = json_decode($data, true, 512, JSON_OBJECT_AS_ARRAY);
         if(is_null($return) && $this->is_strict) throw new ParserException("Cannot parse '{$data}' as Json.");
         return $return;
     }

}