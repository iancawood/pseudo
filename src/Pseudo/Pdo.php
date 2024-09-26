<?php

namespace Pseudo;

use ReturnTypeWillChange;

class Pdo extends \PDO
{
    private ResultCollection $mockedQueries;
    private bool $inTransaction = false;
    private QueryLog $queryLog;

    /**
     * @param ResultCollection|null $collection
     * @param string $dsn
     * @param string|null $username
     * @param string|null $password
     * @param array|null $options
     */
    public function __construct(
        ResultCollection $collection = null
    ) {
        $this->mockedQueries = $collection ?: new ResultCollection();
        $this->queryLog = new QueryLog();
    }

    public function prepare($query, $options = null): PdoStatement
    {
        $result = $this->mockedQueries->getResult($query);
        return new PdoStatement($result, $this->queryLog, $query);
    }

    public function beginTransaction(): bool
    {
        if (!$this->inTransaction) {
            $this->inTransaction = true;
            return true;
        }
        return false;
        // not yet implemented
    }

    public function commit(): bool
    {
        if ($this->inTransaction()) {
            $this->inTransaction = false;
            return true;
        }
        return false;
        // not yet implemented
    }

    public function rollBack(): bool
    {
        if ($this->inTransaction()) {
            $this->inTransaction = false;

            return true;
        }
        // not yet implemented
        return true;
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }

    public function setAttribute($attribute, $value): bool
    {
        // not yet implemented
    }

    public function exec($statement): false|int
    {
        $result = $this->query($statement);
        if ($result) {
            return $result->rowCount();
        }
        return 0;
    }

    /**
     * @param string $query
     * @param int|null $fetchMode
     * @param mixed ...$fetchModeArgs
     * @return PdoStatement
     */
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PdoStatement
    {
        if ($this->mockedQueries->exists($query)) {
            $result = $this->mockedQueries->getResult($query);
            if ($result) {
                $this->queryLog->addQuery($query);
                $statement = new PdoStatement();
                $statement->setResult($result);
                return $statement;
            }
        }
    }

    /**
     * @param null $name
     * @return false|string
     */
    public function lastInsertId($name = null): false|string
    {
        $result = $this->getLastResult();

        return $result->getInsertId();
    }

    /**
     * @return Result
     */
    private function getLastResult(): Result
    {
        $lastQuery = $this->queryLog[count($this->queryLog) - 1];
        $result = $this->mockedQueries->getResult($lastQuery);

        return $result;
    }

    public function errorCode(): ?string
    {
        // not yet implemented
    }

    public function errorInfo(): array
    {
        // not yet implemented
    }

    public function getAttribute($attribute)
    {
        // not yet implemented
    }

    public function quote($string, $type = PDO::PARAM_STR): false|string
    {
        // not yet implemented
    }

    /**
     * @param string $filePath
     */
    public function save($filePath): void
    {
        file_put_contents($filePath, serialize($this->mockedQueries));
    }

    /**
     * @param $filePath
     */
    public function load($filePath): void
    {
        $this->mockedQueries = unserialize(file_get_contents($filePath));
    }

    /**
     * @param $sql
     * @param null $expectedResults
     * @param null $params
     */
    public function mock($sql, $expectedResults = null, $params = null): void
    {
        $this->mockedQueries->addQuery($sql, $expectedResults, $params);
    }

    /**
     * @return ResultCollection
     */
    public function getMockedQueries(): ResultCollection
    {
        return $this->mockedQueries;
    }
}
