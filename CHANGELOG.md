# Change Log

## 1.1.0

**Full Changelog**: https://github.com/ActuallyConnor/pseudo/compare/1.0.6...1.1.0

## 1.0.6

**Full Changelog**: https://github.com/ActuallyConnor/pseudo/compare/1.0.5...1.0.6

## 1.0.5

**Full Changelog**: https://github.com/ActuallyConnor/pseudo/compare/1.0.4...1.0.5

## 1.0.4

**Full Changelog**: https://github.com/ActuallyConnor/pseudo/compare/1.0.3...1.0.4

## 1.0.3

### Changed

- Add ability to throw exception as expected
  result [61c6930](https://github.com/ActuallyConnor/pseudo/commit/61c6930b432b224289ff4b18e6b12bb6dfb963ed)

## 1.0.2

### Changed

- Updating ResultCollection::getResult() to be able to return
  bool [f6e1b2c](https://github.com/ActuallyConnor/pseudo/commit/f6e1b2c78c2b8cac2f400327d8f59cc9b62666ce)

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
