<?php


namespace merger;


class ConservativeUseStatementMergerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function verifySimpleUseStatementMerge()
    {
        $merger = new ConservativeUseStatementMerger();
        $left = '<?php namespace hello;

use \DateTime;
class hello
{

}
';

        $right = '<?php namespace hello;
use \DateTimeZone;
class hello
{

}
';
        $result = 'namespace hello;

use DateTime, DateTimeZone;
class hello
{
}';
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $leftStmts = $parser->parse($left);
        $rightStmts = $parser->parse($right);

        $resultStmts = $merger->merge($leftStmts, $rightStmts);
        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
        $resultCode = $prettyPrinter->prettyPrint($resultStmts);
        $this->assertEquals($result, $resultCode);
    }

    /**
     * @test
     */
    public function verifyCombinedUseStatementMerge()
    {
        $merger = new ConservativeUseStatementMerger();
        $left = '<?php namespace hello;

use \PhpParser\Node\Stmt\UseUse;
class hello
{

}
';

        $right = '<?php namespace hello;
use \PhpParser\Lexer;
class hello
{

}
';
        $result = 'namespace hello;

use PhpParser\Lexer, PhpParser\Node\Stmt\UseUse;
class hello
{
}';
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $leftStmts = $parser->parse($left);
        $rightStmts = $parser->parse($right);

        $resultStmts = $merger->merge($leftStmts, $rightStmts);
        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
        $resultCode = $prettyPrinter->prettyPrint($resultStmts);
        $this->assertEquals($result, $resultCode);
    }

    /**
     * @test
     */
    public function verifyMyEmptyTheirFilledUseStatementMerge()
    {
        $merger = new ConservativeUseStatementMerger();
        $left = '<?php namespace hello;

class hello
{

}
';

        $right = '<?php namespace hello;
use \PhpParser\Lexer;
class hello
{

}
';
        $result = 'namespace hello;

use PhpParser\Lexer;
class hello
{
}';
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $leftStmts = $parser->parse($left);
        $rightStmts = $parser->parse($right);

        $resultStmts = $merger->merge($leftStmts, $rightStmts);
        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
        $resultCode = $prettyPrinter->prettyPrint($resultStmts);
        $this->assertEquals($result, $resultCode);
    }

    /**
     * @test
     */
    public function verifyMyFilledTheirEmptyUseStatementMerge()
    {
        $merger = new ConservativeUseStatementMerger();
        $left = '<?php namespace hello;
use \PhpParser\Lexer;

class hello
{

}
';

        $right = '<?php namespace hello;
class hello
{

}
';
        $result = 'namespace hello;

use PhpParser\Lexer;
class hello
{
}';
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $leftStmts = $parser->parse($left);
        $rightStmts = $parser->parse($right);

        $resultStmts = $merger->merge($leftStmts, $rightStmts);
        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
        $resultCode = $prettyPrinter->prettyPrint($resultStmts);
        $this->assertEquals($result, $resultCode);
    }

    /**
     * @test
     */
    public function verifyNoUseStatementMerge()
    {
        $merger = new ConservativeUseStatementMerger();
        $left = '<?php namespace hello;
class hello
{

}
';

        $right = '<?php namespace hello;
class hello
{

}
';
        $result = 'namespace hello;

class hello
{
}';
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $leftStmts = $parser->parse($left);
        $rightStmts = $parser->parse($right);

        $resultStmts = $merger->merge($leftStmts, $rightStmts);
        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
        $resultCode = $prettyPrinter->prettyPrint($resultStmts);
        $this->assertEquals($result, $resultCode);
    }

}
 