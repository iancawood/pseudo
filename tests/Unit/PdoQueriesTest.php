<?php

declare(strict_types=1);

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\Pdo;
use Pseudo\Result;
use Pseudo\UnitTest\SampleModels\PdoQueries;

class PdoQueriesTest extends TestCase
{
    private Pdo $pdo;
    private PdoQueries $pdoQueries;

    public function setUp() : void
    {
        parent::setUp();

        $this->pdo        = new Pdo();
        $this->pdoQueries = new PdoQueries($this->pdo);
    }

    public function testSelectQueryWithNoParameters() : void
    {
        $this->pdo->mock(
            'SELECT * FROM users',
            [],
            [
                [
                    'id'   => 1,
                    'name' => 'John Doe',
                ]
            ]
        );

        $data = $this->pdoQueries->selectQueryWithNoParameters();
        $this->assertEquals(
            [
                'id'   => 1,
                'name' => 'John Doe',
            ],
            $data
        );
    }
}
