<?php

declare(strict_types=1);

namespace Pseudo\UnitTest\SampleModels;

use PDO;

class PdoQueries
{

    public function __construct(private PDO $pdo)
    {
    }

    public function selectQueryWithNoParameters() : bool|array
    {
        // select all users
        $stmt = $this->pdo->query("SELECT * FROM users");

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function selectSingleRow() : ?array
    {
        // getting the last registered user
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 1");

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function selectMultipleRowsUsingFetch() : array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");

        return array_map(
            fn($row) => $row['name'],
            $stmt->fetch(PDO::FETCH_ASSOC)
        );
    }

    public function selectMultipleRowsUsingFetchAll() : array
    {
        $data = $this->pdo->query("SELECT * FROM users")->fetchAll();

        // and somewhere later:
        return array_map(
            fn($row) => $row['name'],
            $data
        );
    }

    public function selectQueryWithPlaceholders() : ?array
    {
        // select a particular user by id
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([1]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function selectQueryWithNamedPlaceholders() : ?array
    {
        // select a particular user by id
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id' => 1]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function selectingMultipleRowsUsingPlaceholdersAndFetch() : array
    {
        $stmt = $this->pdo->query("SELECT * FROM users LIMIT ?, ?");
        $stmt->execute([10, 3]);

        return array_map(
            fn($row) => $row['name'],
            $stmt->fetch(PDO::FETCH_ASSOC)
        );
    }

    public function selectingMultipleRowsUsingPlaceholdersAndFetchAll() : array
    {
        $stmt = $this->pdo->query("SELECT * FROM users LIMIT ?, ?");
        $stmt->execute([10, 3]);

        return array_map(
            fn($row) => $row['name'],
            $stmt->fetchAll()
        );
    }
}
