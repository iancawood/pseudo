<?php

declare(strict_types=1);

namespace Pseudo\UnitTest\Util\Exceptions;

use Exception;
use PHPUnit\Framework\TestCase;
use Pseudo\Util\Exceptions\UnableToCalculatePositionException;

class UnableToCalculatePositionExceptionTest extends TestCase
{
    /**
     * Test that the exception is thrown with the correct message and code.
     */
    public function testExceptionMessageAndCode()
    {
        $needle    = 'searchTerm';
        $haystack  = 'exampleString';
        $exception = new UnableToCalculatePositionException($needle, $haystack);

        // Assert that the exception message is as expected
        $this->assertEquals(
            "cannot calculate position of searchTerm within exampleString",
            $exception->getMessage()
        );

        // Assert that the exception code is 5
        $this->assertEquals(5, $exception->getCode());
    }

    /**
     * Test that the getNeedle method returns the correct needle.
     */
    public function testGetNeedle()
    {
        $needle    = 'searchTerm';
        $haystack  = 'exampleString';
        $exception = new UnableToCalculatePositionException($needle, $haystack);

        // Assert that getNeedle returns the correct needle
        $this->assertEquals($needle, $exception->getNeedle());
    }

    /**
     * Test that the getHaystack method returns the correct haystack.
     */
    public function testGetHaystack()
    {
        $needle    = 'searchTerm';
        $haystack  = 'exampleString';
        $exception = new UnableToCalculatePositionException($needle, $haystack);

        // Assert that getHaystack returns the correct haystack
        $this->assertEquals($haystack, $exception->getHaystack());
    }

    /**
     * Test that the exception inherits from the base Exception class.
     */
    public function testExceptionInheritance()
    {
        $needle    = 'searchTerm';
        $haystack  = 'exampleString';
        $exception = new UnableToCalculatePositionException($needle, $haystack);

        // Assert that the exception is an instance of Exception
        $this->assertInstanceOf(Exception::class, $exception);
    }
}
