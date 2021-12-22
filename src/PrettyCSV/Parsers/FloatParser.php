<?php

declare(strict_types=1);

namespace PrettyCSV\Parsers;

use PrettyCSV\Interfaces\TypeParserInterface;

class FloatParser implements TypeParserInterface {

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
     * @return float
     * @throws ParserException
     */
    public function parse(string $data): float {
        $return = filter_var($data, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        if(is_null($return) && $this->is_strict) throw new ParserException("Cannot parse '{$data}' as float.");
        return $return;
    }

}