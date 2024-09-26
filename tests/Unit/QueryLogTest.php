<?php

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\QueryLog;

class QueryLogTest extends TestCase
{
    public function testAddQuery()
    {
        $sql = "SELECT foo FROM bar";
        $queryLog = new QueryLog();
        $queryLog->addQuery($sql);
        $queries = $queryLog->getQueries();
        $this->assertEquals(1, count($queries));
        $this->assertTrue($queries[0]->isEqualTo($sql));
    }
}
