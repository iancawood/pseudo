<?php

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\Test\Exception;
use Pseudo\Test\Pseudo;

class ResultCollectionTest extends TestCase
{
    public function testGetResultWithoutMocking()
    {
        $r = new Pseudo\ResultCollection();
        $this->setExpectedException("Pseudo\\Exception");
        $r->getResult("SELECT 1");
    }
    
    public function testDebuggingRawQueries()
    {
        $message = null;
        $r = new Pseudo\ResultCollection();
        try {
            $r->getResult('SELECT 123');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->assertRegExp('/SELECT 123/', $message);
    }
}
