<?php

declare(strict_types=1);

namespace Pseudo\UnitTest;

use PHPUnit\Framework\TestCase;
use Pseudo\Pdo;

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
                'id'   => 1,
                'name' => 'John Doe',
            ]
        );

        $this->expectNotToPerformAssertions();
        $this->pdoQueries->selectQueryWithNoParameters();
    }
}
