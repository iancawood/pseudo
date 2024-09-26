<?php

namespace Pseudo\UnitTest;

use Iterator;
use PHPUnit\Framework\TestCase;
use Pseudo\QueryLog;

class QueryLogTest extends TestCase
{
    public function testGetIterator() : void
    {
        $queryLog = new QueryLog();

        $queryLog->offsetSet('offset', 'value');
        $queries = $queryLog->getIterator();

        $this->assertInstanceOf(Iterator::class, $queries);
        $this->assertEquals(['offset' => 'value'], [...$queries]);
    }

    public function testAddQuery()
    {
        $sql      = "SELECT foo FROM bar";
        $queryLog = new QueryLog();
        $queryLog->addQuery($sql);
        $queries = $queryLog->getQueries();
        $this->assertEquals(1, count($queries));
        $this->assertTrue($queries[0]->isEqualTo($sql));
    }

    public function testOffsetSetUnset() : void
    {
        $queryLog = new QueryLog();

        $queryLog->offsetSet('offset', 'value');
        $queries = $queryLog->getQueries();

        $this->assertEquals(['offset' => 'value'], $queries);

        $queryLog->offsetUnset('offset');
        $queries = $queryLog->getQueries();

        $this->assertEquals([], $queries);
    }
}
