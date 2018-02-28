<?php

namespace App\CoreBundle\Libraries\DQL;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

class DateFormatFunction extends FunctionNode {

     /*
    * holds the timestamp of the DATE_FORMAT DQL statement
    * @var mixed
    */
    protected $dateExpression;
     /**
    * holds the '%format' parameter of the DATE_FORMAT DQL statement
    * @var string
    */
    protected $formatChar;

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
        return 'DATE_FORMAT(' .
                $sqlWalker->walkArithmeticExpression($this->dateExpression) .
                ',' .
                $sqlWalker->walkStringPrimary($this->formatChar) .
                ')';
    }

    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser) {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->ArithmeticExpression();
          
        $parser->match(Lexer::T_COMMA);
        $this->formatChar = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        
    }
}