<?php

declare(strict_types=1);

namespace Pseudo\Util\Exceptions;

use InvalidArgumentException;

class InvalidParameterException extends InvalidArgumentException
{

    protected $argument;

    public function __construct($argument)
    {
        $this->argument = $argument;
        parent::__construct("no SQL string to parse: \n" . $argument, 10);
    }

    public function getArgument()
    {
        return $this->argument;
    }
}
