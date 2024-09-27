<?php

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\ParsedQuery;

class ParsedQueryTest extends TestCase
{
    public function testQueryHashing()
    {
        $sql    = "SELECT foo FROM bar WHERE baz";
        $q      = new ParsedQuery($sql);
        $hashed = sha1($sql);
        $this->assertEquals($hashed, $q->getHash());
    }

    public function testIsEquals()
    {
        $sql = "SELECT foo FROM bar WHERE baz";
        $q1  = new ParsedQuery($sql);
        $q2  = new ParsedQuery($sql);
        $this->assertTrue($q1->isEqualTo($q2));
        $this->assertTrue($q2->isEqualTo($q1));
        $this->assertTrue($q1->isEqualTo($sql));
    }
}
