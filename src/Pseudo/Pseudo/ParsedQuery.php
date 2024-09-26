<?php
namespace Pseudo;

use PHPSQLParser;

class ParsedQuery
{
    private mixed $parsedQuery;
    private string $rawQuery;
    private string $hash;

    /**
     * @param string $query
     */
    public function __construct(string $query)
    {
        $parser = new PHPSQLParser();
        $this->parsedQuery = $parser->parse($query);
        $this->rawQuery = $query;
        $this->hash = sha1(serialize($this->parsedQuery));
    }

    public function isEqualTo($query)
    {
        if (!($query instanceof self)) {
            $query = new self($query);
        }
        return $this->hash === $query->getHash();
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function getRawQuery(): string
    {
        return $this->rawQuery;
    }
}
