# PHP Inspector

A Laravel-friendly tool to inspect and log PHP classes and objects with reflection.

---

## Features

- Log detailed information about a class or object:
  - Class name and parent
  - Interfaces and traits
  - Constants, methods, and properties
- Pretty-printed JSON output for easier debugging
- Can handle objects, classes, arrays, and primitives
- Simple integration with Laravel's logging system (or standard output)

---

## Installation

Install via Composer:

```bash
composer require marcuwynu23/php-inspector
```

For developrnent and testing:

```bash
composer require --dev phpunit/phpunit
```

### Usage

Log a Class

```php
use Marcuwynu23\PHPInspector\PHPInspector;

PHPInspector::log(\DateTime::class);

```

Log an Object

```php
$obj = new stdClass();
$obj->foo = 'bar';

PHPInspector::log($obj);

```

Log an Array or Primitive

```php
PHPInspector::log(['foo' => 'bar']);
PHPInspector::log('Hello World');

```

All output will be printed in JSON format to stdout (or Laravel log if used in Laravel).

### Running Tests

This library uses PHPUnit 10 for testing.
Run tests using Composer.

```bash
composer test

```

This will execute:

```
vendor\bin\phpunit.bat --colors=always

```
