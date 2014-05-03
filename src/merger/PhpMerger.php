<?php

namespace merger;

class PhpMerger
{
    /**
     * @param string $leftFile
     * @param string $rightFile
     */
    public function mergeFiles($myFileCode, $theirFileCode)
    {
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $myStmts = $parser->parse($myFileCode);
        $parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $theirStmts = $parser->parse($theirFileCode);

        $useMerger = new ConservativeUseStatementMerger();
        $resultStmts = $useMerger->merge($myStmts, $theirStmts);
        //Merge use statements.
        //Merge all functions
        //Merge the attributes.
        $prettyPrinter = new \PhpParser\PrettyPrinter\Standard;
        $code = $prettyPrinter->prettyPrint($resultStmts);
        return $code;
    }
}