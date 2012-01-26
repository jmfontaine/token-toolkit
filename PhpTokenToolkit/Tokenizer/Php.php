<?php
/**
 * Copyright (c) 2011-2012, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name PHP Token Toolkit nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL Jean-Marc Fontaine BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PhpTokenToolkit
 * @subpackage Tokenizer
 * @author     Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
namespace PhpTokenToolkit\Tokenizer;

if (!defined('PHP_TOKEN_TOOLKIT_CUSTOM_TOKENS_DEFINED')) {
    define('PHP_TOKEN_TOOLKIT_CUSTOM_TOKENS_DEFINED', true);

    define('T_ANY'                 , 1000);
    define('T_NULL'                , 1001);
    define('T_FALSE'               , 1002);
    define('T_TRUE'                , 1003);
    define('T_THIS'                , 1004);
    define('T_SELF'                , 1005);
    define('T_PARENT'              , 1006);
    define('T_OPEN_PARENTHESIS'    , 1007);
    define('T_CLOSE_PARENTHESIS'   , 1008);
    define('T_OPEN_CURLY_BRACKET'  , 1009);
    define('T_CLOSE_CURLY_BRACKET' , 1010);
    define('T_OPEN_SQUARE_BRACKET' , 1011);
    define('T_CLOSE_SQUARE_BRACKET', 1012);
    define('T_COMMA'               , 1013);
    define('T_SEMICOLON'           , 1014);
    define('T_COLON'               , 1015);
    define('T_CONCAT'              , 1016);
    define('T_EQUAL'               , 1017);
    define('T_GREATER_THAN'        , 1018);
    define('T_LESS_THAN'           , 1019);
    define('T_MULTIPLY'            , 1020);
    define('T_DIVIDE'              , 1021);
    define('T_PLUS'                , 1022);
    define('T_MINUS'               , 1023);
    define('T_MODULUS'             , 1024);
    define('T_POWER'               , 1025);
    define('T_BITWISE_AND'         , 1026);
    define('T_BITWISE_OR'          , 1027);
    define('T_BACKTICK'            , 1028);
    define('T_ERROR_CONTROL'       , 1029);
}

/**
 * Tokenizer for PHP
 *
 * @package    PhpTokenToolkit
 * @subpackage Tokenizer
 * @author     Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright  2011-2012 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license    http://www.opensource.org/licenses/bsd-license.php BSD License
 */
class Php
{
    const EOL_CHAR_MAC     = "\r";
    const EOL_CHAR_UNIX    = "\n";
    const EOL_CHAR_WINDOWS = "\r\n";

    protected $simpleCustomTokens = array(
        'null'   => T_NULL,
        'false'  => T_FALSE,
        'true'   => T_TRUE,
        'this'   => T_THIS,
        'self'   => T_SELF,
        'parent' => T_PARENT,
        '('      => T_OPEN_PARENTHESIS,
        ')'      => T_CLOSE_PARENTHESIS,
        '{'      => T_OPEN_CURLY_BRACKET,
        '}'      => T_CLOSE_CURLY_BRACKET,
        '['      => T_OPEN_SQUARE_BRACKET,
        ']'      => T_CLOSE_SQUARE_BRACKET,
        ','      => T_COMMA,
        ';'      => T_SEMICOLON,
        ':'      => T_COLON,
        '.'      => T_CONCAT,
        '='      => T_EQUAL,
        '>'      => T_GREATER_THAN,
        '<'      => T_LESS_THAN,
        '*'      => T_MULTIPLY,
        '/'      => T_DIVIDE,
        '+'      => T_PLUS,
        '-'      => T_MINUS,
        '%'      => T_MODULUS,
        '^'      => T_POWER,
        '&'      => T_BITWISE_AND,
        '|'      => T_BITWISE_OR,
        '`'      => T_BACKTICK,
        '@'      => T_ERROR_CONTROL,
     );

    protected $customTokensNames = array(
        T_NULL                 => 'T_NULL',
        T_FALSE                => 'T_FALSE',
        T_TRUE                 => 'T_TRUE',
        T_THIS                 => 'T_THIS',
        T_SELF                 => 'T_SELF',
        T_PARENT               => 'T_PARENT',
        T_OPEN_PARENTHESIS     => 'T_OPEN_PARENTHESIS',
        T_CLOSE_PARENTHESIS    => 'T_CLOSE_PARENTHESIS',
        T_OPEN_CURLY_BRACKET   => 'T_OPEN_CURLY_BRACKET',
        T_CLOSE_CURLY_BRACKET  => 'T_CLOSE_CURLY_BRACKET',
        T_OPEN_SQUARE_BRACKET  => 'T_OPEN_SQUARE_BRACKET',
        T_CLOSE_SQUARE_BRACKET => 'T_CLOSE_SQUARE_BRACKET',
        T_COMMA                => 'T_COMMA',
        T_SEMICOLON            => 'T_SEMICOLON',
        T_COLON                => 'T_COLON',
        T_CONCAT               => 'T_CONCAT',
        T_EQUAL                => 'T_EQUAL',
        T_GREATER_THAN         => 'T_GREATER_THAN',
        T_LESS_THAN            => 'T_LESS_THAN',
        T_MULTIPLY             => 'T_MULTIPLY',
        T_DIVIDE               => 'T_DIVIDE',
        T_PLUS                 => 'T_PLUS',
        T_MINUS                => 'T_MINUS',
        T_MODULUS              => 'T_MODULUS',
        T_POWER                => 'T_POWER',
        T_BITWISE_AND          => 'T_BITWISE_AND',
        T_BITWISE_OR           => 'T_BITWISE_OR',
        T_BACKTICK             => 'T_BACKTICK',
        T_ERROR_CONTROL        => 'T_ERROR_CONTROL',
    );

    protected $multilineTokens = array(
        T_CLOSE_TAG,
        T_COMMENT,
        T_CONSTANT_ENCAPSED_STRING,
        T_DOC_COMMENT,
        T_ENCAPSED_AND_WHITESPACE,
        T_INLINE_HTML,
        T_OPEN_TAG,
        T_START_HEREDOC,
        T_WHITESPACE,
    );

    /**
     * Returns token name from token code
     *
     * @param int $tokenCode Token code
     *
     * @throws \InvalidArgumentException If the token code is unknown
     * @return string Token name
     */
    public function getTokenName($tokenCode)
    {
        // First try to get the PHP token name
        $tokenName = token_name($tokenCode);

        // If PHP doesn't know it then it may be a custom token
        if ('UNKNOWN' === $tokenName) {
            if (array_key_exists($tokenCode, $this->customTokensNames)) {
                $tokenName = $this->customTokensNames[$tokenCode];
            } else {
                // If it not a custom token there is a problem
                throw new \InvalidArgumentException(
                    "Invalid token code ($tokenCode)"
                );
            }
        }

        return $tokenName;
    }

    /**
     * Parses a string and returns an array based on PHP tokenizer but augmented with additional data.
     *
     * @param string $string       String to parse
     * @param string $eolCharacter Line ending character for the string
     *
     * @return array Array containing tokens informations
     */
    public function getTokens($string, $eolCharacter = "\n")
    {
        // Retrieve raw tokens
        $rawTokens = token_get_all($string);

        $tokens               = array();
        $tokenType            = null;
        $tokenContent         = '';
        $tokenStartLine       = 1;
        $tokenStartColumn     = 1;
        $tokenEndLine         = 1;
        $tokenEndColumn       = 1;
        $nextTokenStartLine   = 1;
        $nextTokenStartColumn = 1;
        $level                = 1;
        foreach ($rawTokens as $rawToken) {
            // We do not reprocess already processed tokens
            // except if they are strings.
            if (is_array($rawToken) && T_STRING !== $rawToken[0]) {
                $tokenType    = $rawToken[0];
                $tokenContent = $rawToken[1];
            } else {
                // T_STRING tokens
                if (is_array($rawToken)) {
                    $tokenContent = $rawToken[1];
                } else {
                // Raw strings
                    $tokenContent = $rawToken;
                }

                // If the token content matchs a custom token then create it ...
                if (array_key_exists(strtolower($tokenContent), $this->simpleCustomTokens)) {
                    $tokenType = $this->simpleCustomTokens[strtolower($tokenContent)];
                } else {
                // ... else consider it a simple string.
                    $tokenType = T_STRING;
                }
            }

            // Token line number can only change in tokens that span several
            // lines.
            if (in_array($tokenType, $this->multilineTokens)
                && false !== strpos($tokenContent, $eolCharacter)) {
                $parts         = explode($eolCharacter, $tokenContent);
                $lastPartIndex = count($parts) - 1;

                $tokenStartLine   = $nextTokenStartLine;
                $tokenStartColumn = $nextTokenStartColumn;

                // EOL character is the last character of the token content
                if (0 === strlen($parts[$lastPartIndex])) {
                    $tokenEndLine   = $tokenStartLine + count($parts) - 2;
                    $tokenEndColumn = $nextTokenStartColumn + strlen($parts[$lastPartIndex - 1] . $eolCharacter) - 1;

                    $nextTokenStartLine   = $tokenEndLine + 1;
                    $nextTokenStartColumn = 1;
                } else {
                    $tokenEndLine   = $tokenStartLine + count($parts) - 1;
                    $tokenEndColumn = strlen($parts[$lastPartIndex]);

                    $nextTokenStartLine   = $tokenEndLine;
                    $nextTokenStartColumn = strlen($parts[$lastPartIndex]) + 1;
                }
            } else {
                $tokenStartLine   = $nextTokenStartLine;
                $tokenEndLine     = $nextTokenStartLine; // Token is on one line
                $tokenStartColumn = $nextTokenStartColumn;
                $tokenEndColumn   = $tokenStartColumn + strlen($tokenContent) - 1;

                $nextTokenStartColumn = $tokenEndColumn + 1;
            }

            // Increase level on opening curly brackets
            if (T_OPEN_CURLY_BRACKET === $tokenType) {
                $level++;
            }

            // Finally add the token to the stack
            $tokens[] = array(
                'type'        => $tokenType,
                'content'     => $tokenContent,
                'startLine'   => $tokenStartLine,
                'startColumn' => $tokenStartColumn,
                'endLine'     => $tokenEndLine,
                'endColumn'   => $tokenEndColumn,
                'level'       => $level,
            );

            // Decrease level on closing brackets after it has been added to the array
            // so that it has the same level as the scope is closes.
            if (T_CLOSE_CURLY_BRACKET === $tokenType) {
                $level--;
            }
        }

        return $tokens;
    }
}