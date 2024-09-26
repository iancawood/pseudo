<?php

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\Exception;
use Pseudo\ResultCollection;

class ResultCollectionTest extends TestCase
{
    public function testGetResultWithoutMocking()
    {
        $r = new ResultCollection();
        $this->expectException(Exception::class);
        $r->getResult("SELECT 1");
    }

    public function testDebuggingRawQueries()
    {
        $message = null;
        $r = new ResultCollection();
        try {
            $r->getResult('SELECT 123');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->assertMatchesRegularExpression('/SELECT 123/', $message);
    }
}
