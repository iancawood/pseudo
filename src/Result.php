<?php

namespace Pseudo;

use Pseudo\Exceptions\Exception;

class Result
{
    private array $rows = [];
    private bool $isParameterized = false;
    private string $errorCode;
    private string $errorInfo;
    private int $affectedRowCount = 0;
    private int $insertId = 0;
    private int $rowOffset = 0;
    private array $params = [];

    public function __construct($rows = null, $params = null)
    {
        if (is_array($rows)) {
            if ($params) {
                $this->rows[$this->stringifyParameterSet($params)] = $rows;
                $this->isParameterized = true;
            } else {
                $this->rows = $rows;
            }
        }
    }

    public function addRow(array $row, $params = null): void
    {
        if ($params) {
            if ($this->isParameterized && !empty($row)) {
                $this->rows[$this->stringifyParameterSet($params)][] = $row;
            } else {
                if (!$this->isParameterized && isset($this->rows) && !$this->rows) {
                    if (!empty($row)) {
                        $this->rows[$this->stringifyParameterSet($params)][] = $row;
                    }
                    $this->isParameterized = true;
                }
            }
        } else {
            if (!$this->isParameterized && !empty($row)) {
                $this->rows[] = $row;
            } else {
                throw new Exception("Cannot mix parameterized and non-parameterized rowsets");
            }
        }
    }

    public function setParams($params, $parameterize = null): void
    {
        $this->params = $params;
        if ($parameterize) {
            $this->isParameterized = true;
        }
    }

    public function getRows(array $params = [])
    {
        if (!empty($this->params) && empty($params)) {
            $params = $this->params;
        }
        if ($params) {
            if ($this->isParameterized) {
                if (isset($this->rows[$this->stringifyParameterSet($params)])) {
                    return $this->rows[$this->stringifyParameterSet($params)];
                } else {
                    return [];
                }
            }

            throw new Exception("Cannot get rows with parameters on a non-parameterized result");
        } else {
            if (!$this->isParameterized && isset($this->rows)) {
                return $this->rows;
            }
            throw new Exception("Cannot get rows without parameters on a parameterized result");
        }
    }

    /**
     * Returns the next row if it exists, otherwise returns false
     *
     * @param array $rows Rows to get row from
     * @return false|array Next row (false if doesn't exist)
     */
    private function getRowIfExists(array $rows): false|array
    {
        if (!isset($rows[$this->rowOffset])) {
            return false;
        }
        return $rows[$this->rowOffset];
    }

    /**
     * Returns the next available row if it exists, otherwise returns false
     *
     * @return false|array
     */
    public function nextRow(): false|array
    {
        if (!isset($this->rows)) {
            return false;
        }

        if ($this->isParameterized) {
            $row = $this->getRowIfExists($this->rows[$this->stringifyParameterSet($this->params)]);
        } else {
            $row = $this->getRowIfExists($this->rows);
        }

        if ($row) {
            $this->rowOffset++;
        }

        return $row;
    }


    public function setInsertId($insertId): void
    {
        $this->insertId = $insertId;
    }

    public function getInsertId(): int
    {
        return $this->insertId;
    }

    /**
     * @param $errorCode
     * @throws Exception
     */
    public function setErrorCode($errorCode): void
    {
        if (ctype_alnum($errorCode) && strlen($errorCode) == 5) {
            $this->errorCode = $errorCode;
        } else {
            throw new Exception("Error codes must be in ANSI SQL standard format");
        }
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @param $errorInfo
     */
    public function setErrorInfo($errorInfo): void
    {
        $this->errorInfo = $errorInfo;
    }

    /**
     * @return string
     */
    public function getErrorInfo(): string
    {
        return $this->errorInfo;
    }

    public function setAffectedRowCount($affectedRowCount): void
    {
        $this->affectedRowCount = $affectedRowCount;
    }

    public function getAffectedRowCount(): int
    {
        return $this->affectedRowCount;
    }

    public function isOrdinalArray(array $arr): bool
    {
        return !(is_string(key($arr)));
    }

    public function reset(): void
    {
        $this->rowOffset = 0;
    }

    private function stringifyParameterSet(array $params): string
    {
        if ($this->isOrdinalArray($params)) {
            return implode(',', $params);
        } else {
            $returnArray = [];
            foreach ($params as $key => $value) {
                $returnArray[] = $key;
                $returnArray[] = $value;
            }
            return implode(',', $returnArray);
        }
    }
}
