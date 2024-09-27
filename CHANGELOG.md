# Change Log

## 1.0.1

### Changed

- Add ability to return false as the query
  response [ef88d6d](https://github.com/ActuallyConnor/pseudo/commit/ef88d6d0709fb16b7ecb343b60c4b8cf96cebe2c)

## 1.0.0

### Changed

- Library forked from [jimbosjb/pseudo](https://github.com/jimbojsb/pseudo)
- Connor take ownership
- Update entire library to be compatible for PHP 8.x
- `README.md` updates
- Restructuring exceptions directory
- Add support for `Pseudo\PdoStatement::getIterator()`
- Dropping dead code
- Repairing `composer.json`
- Rename `PdoException` class to `PseudoPdoException`
- Rename `Exception` class to `PseudoException`
- Refactor method signature for `Pseudo\Pdo::mock()`
    - Change `Pseudo\Pdo::mock(string $sql, $params = null, $expectedResults = null)` 
