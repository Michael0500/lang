<?php
require_once 'lang.php';

$src = "2 +2-51 * 44-
17
";
$lex = new Lexer($src);

while (true) {
    $tok = $lex->next();

    echo $tok;
    echo PHP_EOL;

    if ($tok->type() === TokenType::T_EOF) {
        break;
    }
}
