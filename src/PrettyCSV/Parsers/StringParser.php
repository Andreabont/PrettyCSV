<?php

declare(strict_types=1);

namespace PrettyCSV\Parsers;

use PrettyCSV\Interfaces\TypeParserInterface;

class StringParser implements TypeParserInterface {

    /**
     * @param string $data
     * @return string
     */
     public function parse(string $data): string {
         return $data;
     }

}