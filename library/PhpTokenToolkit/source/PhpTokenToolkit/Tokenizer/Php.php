<?php
namespace PhpTokenToolkit\Tokenizer;

if (!defined('PHP_TOKEN_TOOLKIT_CUSTOM_TOKENS_DEFINED')) {
	define('PHP_TOKEN_TOOLKIT_CUSTOM_TOKENS_DEFINED', true);

	define('T_NULL'                , 1000);
	define('T_FALSE'               , 1001);
	define('T_TRUE'                , 1002);
	define('T_THIS'                , 1003);
	define('T_SELF'                , 1004);
	define('T_PARENT'              , 1005);
	define('T_OPEN_PARENTHESIS'    , 1006);
	define('T_CLOSE_PARENTHESIS'   , 1007);
	define('T_OPEN_CURLY_BRACKET'  , 1008);
	define('T_CLOSE_CURLY_BRACKET' , 1009);
	define('T_OPEN_SQUARE_BRACKET' , 1010);
	define('T_CLOSE_SQUARE_BRACKET', 1011);
	define('T_COMMA'               , 1012);
	define('T_SEMICOLON'           , 1013);
	define('T_COLON'               , 1014);
	define('T_CONCAT'              , 1015);
	define('T_EQUAL'               , 1016);
	define('T_GREATER_THAN'        , 1017);
	define('T_LESS_THAN'           , 1018);
	define('T_MULTIPLY'            , 1019);
	define('T_DIVIDE'              , 1020);
	define('T_PLUS'                , 1021);
	define('T_MINUS'               , 1022);
	define('T_MODULUS'             , 1023);
	define('T_POWER'               , 1024);
	define('T_BITWISE_AND'         , 1025);
	define('T_BITWISE_OR'          , 1026);
	define('T_BACKTICK'            , 1027);
}

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

    public function getTokenName($tokenCode)
    {
        // First try to get the PHP token name
        $tokenName = token_name($tokenCode);

        // If PHP doesn't know it then it should be a custom token
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
                && false !== strpos($tokenContent, $eolCharacter))  {
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

            // Finally add the token to the stack
            $tokens[] = array(
                $tokenType,
                $tokenContent,
                $tokenStartLine,
                $tokenStartColumn,
                $tokenEndLine,
                $tokenEndColumn
            );
        }

        return $tokens;
    }

    public function isCustom($tokenCode)
    {
        return in_array($tokenCode, $this->simpleCustomTokens);
    }
}