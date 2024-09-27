<?php

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\Exceptions\PseudoException;
use Pseudo\ResultCollection;

class ResultCollectionTest extends TestCase
{
    public function testGetResultWithoutMocking()
    {
        $r = new ResultCollection();
        $this->expectException(PseudoException::class);
        $r->getResult("SELECT 1");
    }

    public function testDebuggingRawQueries()
    {
        $message = null;
        $r = new ResultCollection();
        try {
            $r->getResult('SELECT 123');
        } catch (PseudoException $e) {
            $message = $e->getMessage();
        }
        $this->assertMatchesRegularExpression('/SELECT 123/', $message);
    }

    public function testAddQuery(): void
    {
        $results = new ResultCollection();
        $results->addQuery("SELECT * FROM ?", ['table'], [['test']]);

        $this->assertEquals($results->count(), 1);
    }
}
