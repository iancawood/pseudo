<?php

declare(strict_types=1);

namespace Pseudo\UnitTest\Util\Exceptions;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Pseudo\Util\Exceptions\InvalidParameterException;

class InvalidParameterExceptionTest extends TestCase
{
    /**
     * Test that the exception is thrown with the correct message and code.
     */
    public function testExceptionMessageAndCode()
    {
        $argument  = "Invalid SQL Parameter";
        $exception = new InvalidParameterException($argument);

        // Assert that the exception message is as expected
        $this->assertEquals("no SQL string to parse: \n" . $argument, $exception->getMessage());

        // Assert that the exception code is 10
        $this->assertEquals(10, $exception->getCode());
    }

    /**
     * Test that the getArgument method returns the correct argument.
     */
    public function testGetArgument()
    {
        $argument  = "Invalid SQL Parameter";
        $exception = new InvalidParameterException($argument);

        // Assert that getArgument returns the correct argument
        $this->assertEquals($argument, $exception->getArgument());
    }

    /**
     * Test that the exception inherits from InvalidArgumentException.
     */
    public function testExceptionInheritance()
    {
        $argument  = "Invalid SQL Parameter";
        $exception = new InvalidParameterException($argument);

        // Assert that the exception is an instance of InvalidArgumentException
        $this->assertInstanceOf(InvalidArgumentException::class, $exception);
    }
}
