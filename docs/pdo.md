# `Pseudo/Pdo` Class Documentation

The `Pdo` class extends the built-in `\PDO` class and is part of the `Pseudo` namespace. It is designed to allow for mock query handling and provides additional functionality to manage transactions, query logging, and result mocking.

## Namespace

```php
namespace Pseudo;
```

## Dependencies

- `InvalidArgumentException`
- `Pseudo\Exceptions\PseudoException`
- `Throwable`

## Properties

- `private ResultCollection $mockedQueries`: A collection of mocked query results.
- `private bool $inTransaction`: Tracks the transaction state.
- `private QueryLog $queryLog`: A log of executed queries.

## Constructor

### `__construct(ResultCollection $collection = null)`

Initializes the `Pdo` instance.

- **Parameters:**
    - `ResultCollection|null $collection`: An optional collection of mocked queries. If not provided, a new `ResultCollection` is created.

- **Example:**
  ```php
  $pdo = new Pdo($resultCollection);
  ```

## Methods

### `prepare($query, $options = null): PdoStatement`

Prepares a mock query and returns a `PdoStatement`.

- **Parameters:**
    - `$query`: The SQL query string.
    - `$options`: Optional parameters.

- **Returns:** `PdoStatement`
- **Throws:** `PseudoException`, `Throwable`

- **Example:**
  ```php
  $stmt = $pdo->prepare("SELECT * FROM users");
  ```

### `beginTransaction(): bool`

Starts a new transaction.

- **Returns:** `bool` (`true` if a transaction is started, `false` if already in a transaction)

- **Example:**
  ```php
  $pdo->beginTransaction();
  ```

### `commit(): bool`

Commits the current transaction.

- **Returns:** `bool` (`true` if the transaction is successfully committed, `false` if no active transaction)

- **Example:**
  ```php
  $pdo->commit();
  ```

### `rollBack(): bool`

Rolls back the current transaction.

- **Returns:** `bool` (`true` if the transaction is successfully rolled back, `false` if no active transaction)

- **Example:**
  ```php
  $pdo->rollBack();
  ```

### `inTransaction(): bool`

Checks if there is an active transaction.

- **Returns:** `bool` (`true` if in a transaction, `false` otherwise)

- **Example:**
  ```php
  if ($pdo->inTransaction()) {
      // Do something
  }
  ```

### `exec($statement): false|int`

Executes an SQL statement and returns the number of affected rows.

- **Parameters:**
    - `$statement`: The SQL query string.

- **Returns:** `int` (number of affected rows), `false` on failure

- **Example:**
  ```php
  $affectedRows = $pdo->exec("DELETE FROM users WHERE id = 1");
  ```

### `query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PdoStatement`

Executes an SQL query and returns a `PdoStatement`.

- **Parameters:**
    - `string $query`: The SQL query string.
    - `?int $fetchMode`: Optional fetch mode.
    - `mixed ...$fetchModeArgs`: Optional arguments for fetch mode.

- **Returns:** `PdoStatement`
- **Throws:** `PseudoException`, `Throwable`

- **Example:**
  ```php
  $stmt = $pdo->query("SELECT * FROM users");
  ```

### `lastInsertId($name = null): false|string`

Returns the ID of the last inserted row, or `false` if no such ID exists.

- **Parameters:**
    - `$name`: Optional name of the sequence object.

- **Returns:** `string` (the ID of the last inserted row), `false` on failure

- **Example:**
  ```php
  $id = $pdo->lastInsertId();
  ```

### `save(string $filePath): void`

Saves the current mocked query collection to a file.

- **Parameters:**
    - `string $filePath`: The path to the file.

- **Example:**
  ```php
  $pdo->save('queries.txt');
  ```

### `load($filePath): void`

Loads a mocked query collection from a file.

- **Parameters:**
    - `$filePath`: The path to the file.

- **Example:**
  ```php
  $pdo->load('queries.txt');
  ```

### `mock(string $sql, ?array $params = null, mixed $expectedResults = null): void`

Mocks a query and its expected result.

- **Parameters:**
    - `string $sql`: The SQL query string.
    - `?array $params`: Optional query parameters.
    - `mixed $expectedResults`: The expected result of the query.

- **Example:**
  ```php
  $pdo->mock("SELECT * FROM users", null, $expectedResults);
  ```

### `getMockedQueries(): ResultCollection`

Returns the current mocked queries collection.

- **Returns:** `ResultCollection`

- **Example:**
  ```php
  $mockedQueries = $pdo->getMockedQueries();
  ```

## Exceptions

- **`PseudoException`:** Thrown when there is an issue with query handling or execution.

## Usage Example

```php
$pdo = new Pdo();
$pdo->mock("SELECT * FROM users", null, [['id' => 1, 'name' => 'John Doe']]);

$stmt = $pdo->query("SELECT * FROM users");
$results = $stmt->fetchAll();
```
