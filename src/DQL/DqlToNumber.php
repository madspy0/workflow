<?php

namespace App\DQL;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class DqlToNumber extends FunctionNode
{
    public $field;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker): string
    {
        return 'TO_NUMBER(' .
            $this->field->dispatch($sqlWalker) .
            ')';
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser) {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
