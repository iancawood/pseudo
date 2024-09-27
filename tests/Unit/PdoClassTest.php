<?php

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\Exceptions\Exception;
use Pseudo\Pdo;
use Pseudo\PdoStatement;
use Pseudo\Result;
use Pseudo\ResultCollection;

class PdoClassTest extends TestCase
{
    public function testPrepare()
    {
        $sql = "SELECT * FROM test WHERE foo='bar'";
        $p   = new Pdo();
        $p->mock($sql);
        $statement = $p->prepare($sql);
        $this->assertInstanceOf(PdoStatement::class, $statement);
    }

    public function testBeginTransaction() : void
    {
        $pdo     = new Pdo();
        $success = $pdo->beginTransaction();
        $this->assertTrue($success);
    }

    public function testTransactionStates()
    {
        $p = new Pdo();
        $this->assertEquals($p->inTransaction(), false);

        $this->assertEquals($p->beginTransaction(), true);
        $this->assertEquals($p->inTransaction(), true);

        $this->assertEquals($p->commit(), true);
        $this->assertEquals($p->inTransaction(), false);
        $this->assertEquals($p->rollBack(), false);

        $p->beginTransaction();
        $this->assertEquals($p->beginTransaction(), false);
        $this->assertEquals($p->inTransaction(), true);
        $this->assertEquals($p->rollBack(), true);
        $this->assertEquals($p->commit(), false);
    }

    public function testMock()
    {
        $sql1    = "SELECT * FROM test WHERE foo='bar'";
        $result1 = [
            [
                'id'  => 1,
                'foo' => 'bar'
            ]
        ];

        $p = new Pdo();
        $p->mock($sql1, $result1);
        $queries = $p->getMockedQueries();
        $this->assertTrue($queries->exists($sql1));

        $sql2    = "SELECT * FROM test WHERE foo=:param1";
        $params2 = ["param1" => "bar"];

        $sql3    = "SELECT * FROM test WHERE foo=?";
        $params3 = ['bar'];

        $params4 = ['baz'];
        $result2 = [
            [
                'id'  => 2,
                'foo' => 'baz'
            ]
        ];

        $p->mock($sql2, $result1, $params2);
        $p->mock($sql3, $result1, $params3);
        $p->mock($sql3, $result2, $params4);

        $this->assertEquals(3, count($p->getMockedQueries()));
    }


    public function testQueryReturnsMockedResults()
    {
        $p            = new Pdo();
        $expectedRows = new Result();
        $expectedRows->addRow(
            [
                "foo" => "bar",
                "id"  => 1
            ]
        );
        $p->mock("SELECT * FROM test WHERE foo='bar'", $expectedRows);
        $result = $p->query("SELECT * FROM test WHERE foo='bar'");
        $this->assertEquals($expectedRows->getRows(), $result->fetchAll(PDO::FETCH_ASSOC));
    }

    public function testMockQueryDoesNotExist() : void
    {
        $pdo = new Pdo();

        $this->expectException(Exception::class);
        $pdo->query('SELECT * FROM users');
    }

    public function testLastInsertId()
    {
        $sql = "INSERT INTO foo VALUES ('1')";
        $r   = new Result();
        $p   = new Pdo();
        $p->mock($sql, $r);
        $p->query($sql);
        $this->assertEquals(0, $p->lastInsertId());
        $r->setInsertId(1);
        $p->query($sql);
        $this->assertEquals(1, $p->lastInsertId());
    }

    public function testFailsToGetLastInsertId() : void
    {
        $pdo = new Pdo();

        $this->assertFalse($pdo->lastInsertId());
    }

    public function testLastInsertIdPreparedStatement()
    {
        $sql = "SELECT * FROM test WHERE foo='bar'";
        $p   = new Pdo();
        $r   = new Result();
        $r->setInsertId(10);
        $p->mock($sql, $r);
        $statement = $p->prepare($sql);
        $statement->execute();
        $this->assertEquals(10, $p->lastInsertId());
    }

    public function testErrorInfo()
    {
        $sql = "SELECT 1";
        $r   = new Result();
        $p   = new Pdo();
        $p->mock($sql, $r);
        $p->query($sql);
        $this->assertEquals(0, $p->lastInsertId());
        $r->setInsertId(1);
        $p->query($sql);
        $this->assertEquals(1, $p->lastInsertId());
    }

    public function testErrorCode()
    {
        $sql = "SELECT 1";
        $r   = new Result();
        $p   = new Pdo();
        $p->mock($sql, $r);
        $p->query($sql);
        $this->assertEquals(0, $p->lastInsertId());
        $r->setInsertId(1);
        $p->query($sql);
        $this->assertEquals(1, $p->lastInsertId());
    }

    public function testExec()
    {
        $sql = "SELECT 1";
        $p   = new Pdo();
        $r   = new Result();
        $p->mock($sql, $r);
        $results = $p->exec($sql);
        $this->assertEquals(0, $results);
        $r->setAffectedRowCount(5);
        $this->assertEquals(5, $p->exec($sql));
    }

    public function testLoad()
    {
        $r = new ResultCollection();
        $r->addQuery("SELECT 1", null, [[1]]);
        $serialized = serialize($r);
        if (file_exists('testload')) {
            unlink('testload');
        }
        file_put_contents('testload', $serialized);
        $p = new Pdo();
        $p->load('testload');
        $this->assertEquals($r, $p->getMockedQueries());
        unlink('testload');
    }

    public function testSave()
    {
        $r = new ResultCollection();
        $r->addQuery("SELECT 1", null, [[1]]);
        $serialized = serialize($r);
        if (file_exists('testsave')) {
            unlink('testsave');
        }
        $p = new Pdo($r);
        $p->save('testsave');
        $queries = unserialize(file_get_contents('testsave'));
        $this->assertEquals($r, $queries);
        unlink('testsave');
    }

    public function testDebuggingRawQueries()
    {
        $message = null;
        $p       = new Pdo();
        try {
            $p->prepare('SELECT 123');
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        $this->assertMatchesRegularExpression('/SELECT 123/', $message);
    }
}