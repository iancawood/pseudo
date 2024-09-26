<?php

declare(strict_types=1);

namespace Pseudo\Util\Exceptions;

use Exception;

class UnableToCalculatePositionException extends Exception
{

    protected $needle;
    protected $haystack;

    public function __construct($needle, $haystack)
    {
        $this->needle   = $needle;
        $this->haystack = $haystack;
        parent::__construct("cannot calculate position of " . $needle . " within " . $haystack, 5);
    }

    public function getNeedle()
    {
        return $this->needle;
    }

    public function getHaystack()
    {
        return $this->haystack;
    }
}
