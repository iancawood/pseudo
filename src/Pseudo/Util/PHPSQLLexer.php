<?php

declare(strict_types=1);

namespace Pseudo\Util;

/**
 * This class splits the SQL string into little parts, which the parser can
 * use to build the result array.
 *
 * @author arothe
 *
 */
class PHPSQLLexer extends PHPSQLParserUtils
{

    public function __construct()
    {
        parent::__construct();
    }

    public function split(string $sql) : array
    {
        $tokens   = array();
        $token    = "";
        $splitter = array(
            "\r\n",
            "!=",
            ">=",
            "<=",
            "<>",
            "\\",
            "&&",
            ">",
            "<",
            "|",
            "=",
            "^",
            "(",
            ")",
            "\t",
            "\n",
            "'",
            "\"",
            "`",
            ",",
            "@",
            " ",
            "+",
            "-",
            "*",
            "/",
            ";"
        );

        while (strlen($sql) > 0) {
            $idx = $this->startsWith($splitter, $sql);
            if ($idx === false) {
                $token .= $sql[0];
                $sql   = substr($sql, 1);
                continue;
            }

            if ($token !== "") {
                $tokens[] = $token;
            }
            $tokens[] = $splitter[$idx];
            $sql      = substr($sql, strlen($splitter[$idx]));
            $token    = "";
        }

        if ($token !== "") {
            $tokens[] = $token;
        }

        $tokens = $this->concatEscapeSequences($tokens);
        $tokens = $this->balanceBackticks($tokens);
        $tokens = $this->concatColReferences($tokens);
        $tokens = $this->balanceParenthesis($tokens);

        return $tokens;
    }

    private function balanceBackticks(array $tokens) : array
    {
        $i   = 0;
        $cnt = count($tokens);
        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }

            $token = $tokens[$i];

            if (in_array($token, array("'", "\"", "`"))) {
                $tokens = $this->balanceCharacter($tokens, $i, $token);
            }

            $i++;
        }

        return $tokens;
    }

    # backticks are not balanced within one token, so we have
    # to re-combine some tokens
    private function balanceCharacter(array $tokens, int $idx, string $char) : array
    {
        $token_count = count($tokens);
        $i           = $idx + 1;
        while ($i < $token_count) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }

            $token        = $tokens[$i];
            $tokens[$idx] .= $token;
            unset($tokens[$i]);

            if ($token === $char) {
                break;
            }

            $i++;
        }

        return array_values($tokens);
    }

    /*
     * does the token ends with dot?
     * concat it with the next token
     *
     * does the token starts with a dot?
     * concat it with the previous token
     */
    private function concatColReferences(array $tokens) : array
    {
        $cnt = count($tokens);
        $i   = 0;
        while ($i < $cnt) {
            if (!isset($tokens[$i])) {
                $i++;
                continue;
            }

            $trim = trim($tokens[$i]);

            if ($this->startsWith($tokens[$i], '.') !== false) {
                // concat the previous tokens, till the token has been changed
                $k   = $i - 1;
                $len = strlen($tokens[$i]);
                while (($k >= 0) && ($len == strlen($tokens[$i]))) {
                    if (!isset($tokens[$k])) {
                        $k--;
                        continue;
                    }
                    $tokens[$i] = $tokens[$k] . $tokens[$i];
                    unset($tokens[$k]);
                    $k--;
                }
            }

            if ($this->endsWith($tokens[$i], '.')) {
                // concat the next tokens, till the token has been changed
                $k   = $i + 1;
                $len = strlen($tokens[$i]);
                while (($k < $cnt) && ($len == strlen($tokens[$i]))) {
                    if (!isset($tokens[$k])) {
                        $k++;
                        continue;
                    }
                    $tokens[$i] .= $tokens[$k];
                    unset($tokens[$k]);
                    $k++;
                }
            }

            $i++;
        }

        return array_values($tokens);
    }

    private function concatEscapeSequences(array $tokens) : array
    {
        $tokenCount = count($tokens);
        $i          = 0;
        while ($i < $tokenCount) {
            if ($this->endsWith($tokens[$i], "\\")) {
                $i++;
                if (isset($tokens[$i])) {
                    $tokens[$i - 1] .= $tokens[$i];
                    unset($tokens[$i]);
                }
            }
            $i++;
        }

        return array_values($tokens);
    }

    private function balanceParenthesis(array $tokens) : array
    {
        $token_count = count($tokens);
        $i           = 0;
        while ($i < $token_count) {
            if ($tokens[$i] !== '(') {
                $i++;
                continue;
            }
            $count = 1;
            for ($n = $i + 1; $n < $token_count; $n++) {
                $token = $tokens[$n];
                if ($token === '(') {
                    $count++;
                }
                if ($token === ')') {
                    $count--;
                }
                $tokens[$i] .= $token;
                unset($tokens[$n]);
                if ($count === 0) {
                    $n++;
                    break;
                }
            }
            $i = $n;
        }

        return array_values($tokens);
    }
}
