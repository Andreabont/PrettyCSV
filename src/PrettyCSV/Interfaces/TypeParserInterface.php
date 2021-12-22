<?php

declare(strict_types=1);

namespace PrettyCSV\Interfaces;

interface TypeParserInterface {

    /**
     * @param string $data
     * @return mixed
     */
    public function parse(string $data) : mixed;

}