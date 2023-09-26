<?php

function makeToken($type, $value): Token
{
    return new Token($type, $value);
}

class TokenType
{
    const
        T_EOF = -1,

        T_PLUS = 1,
        T_MINUS = 2,
        T_MULT = 3,
        T_DIV = 4,

        T_NUMBER = 5,

        T_ERROR = 6;

    const TYPE_NAME = [
        self::T_EOF => 'EOF',
        self::T_PLUS => 'PLUS',
        self::T_MINUS => 'MINUS',
        self::T_MULT => 'MULT',
        self::T_DIV => 'DIV',
        self::T_NUMBER => 'NUMBER',
        self::T_ERROR => 'ERROR',
    ];
}

class Token
{
    private $type;
    private $value;

    /**
     * Token constructor.
     * @param  $type
     * @param $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function type()
    {
        return $this->type;
    }

    public function __toString()
    {
        return "{$this->typeAsString()}: {$this->value()}";
    }

    /**
     * @return mixed
     */
    public function typeAsString()
    {
        return TokenType::TYPE_NAME[$this->type];
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

}

class Lexer
{
    private string $src;
    private int $len;
    private int $pos;

    /**
     * Lexer constructor.
     * @param string $src
     */
    public function __construct(string $src)
    {
        $this->src = $src;
        $this->pos = 0;
        $this->len = strlen($this->src);
    }

    public function next(): Token
    {
        while (!$this->eof()) {
            switch ($this->curr()) {
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9':
                    return $this->nextNumber();
                case '+':
                    $this->pos++;
                    return makeToken(TokenType::T_PLUS, '');
                case '-':
                    $this->pos++;
                    return makeToken(TokenType::T_MINUS, '');
                case '*':
                    $this->pos++;
                    return makeToken(TokenType::T_MULT, '');
                case '/':
                    $this->pos++;
                    return makeToken(TokenType::T_DIV, '');
                case " ":
                case "\t":
                case "\r":
                case "\n":
                    $this->pos++;
                    break;
                default:
                    $this->pos++;
                    return makeToken(TokenType::T_ERROR, 'Unknown symbol');
            }
        }

        if ($this->eof()) {
            return makeToken(TokenType::T_EOF, '');
        }
    }

    private function eof(): bool
    {
        return $this->pos >= $this->len;
    }

    private function curr(): string
    {
        return $this->src[$this->pos];
    }

    private function nextNumber(): Token
    {
        $val = '';
        while (!$this->eof() && in_array($this->curr(), ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], true)) {
            $val .= $this->curr();
            $this->pos++;
        }

        return makeToken(TokenType::T_NUMBER, $val);
    }
}
