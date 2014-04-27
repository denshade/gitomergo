<?php

namespace merger;

use merger\usestatementmerger\UseStatementTraverser;
use PhpParser\Lexer;
use PhpParser\Parser;
use merger\ChangedNode;

class ConservativeUseStatementMerger
{
    /**
     * @param ChangedNode[] $myFile
     * @param ChangedNode[] $theirFile
     */
    public function merge(array $myStatements, array $theirStatements)
    {
        $myUseStatements    =   $this->filterOutUseStatements($myStatements);
        $theirUseStatements =   $this->filterOutUseStatements($theirStatements);
        $fullQualifiedNameMap = array();
        foreach($myUseStatements as $myUseStatement)
        {
            /**
             * @var UseUse $myUseStatement
             */
            $fullQualifiedNameMap[$myUseStatement->getName()] = $myUseStatement->isAddedUse();
        }
        foreach($theirUseStatements as $theirUseStatement)
        {
            /**
             * @var ChangedNode $myUseStatement
             */
            $fullQualifiedNameMap[$theirUseStatement->getName()] = $theirUseStatement->isAddedUse();
        }

        $this->writeUseStatements($myStatements, $fullQualifiedNameMap);

        /*
         * Get my change set on use statements.
         * Get their change set on use statements.
         * Get the added use statements in myFile.
         * Those are added anyway.
         * Get the locally removed use statements.
         *
         * Sort the use statements by name.
         * If the use statement is added AND removed. It is part of the use statements.
         * If the use statement is added. It is part of the use statements.
         *
         * If the use statement is removed from my code and nothing happens to their code. Cleaned by the unused use statement detector.
         *
         *
         * (Followed by a unused use statement detector).
         */
/*        $myUseStatements = $this->getUseStatements($myFile);
        $theirUseStatements = $this->getUseStatements($theirUseStatements);
        $resultUseStatements = array_unique(array_merge($theirUseStatements, $myUseStatements));
        return $resultUseStatements;
*/
    }

    /**
     * @param $filePath
     * @return \string[]
     */
    private function getUseStatements($filePath)
    {
        $myFileContents = file_get_contents($filePath);

        $parser = new Parser(new Lexer());

        try {
            $stmts = $parser->parse($myFileContents);
            $traverser     = new \PhpParser\NodeTraverser;
            $useVisitor = new UseStatementTraverser();
            $traverser->addVisitor($useVisitor);
            $traverser->traverse($stmts);
            return $useVisitor->getFullUseStatements();
        } catch (PhpParser\Error $e) {
            echo 'Parse Error: ', $e->getMessage();
        }
    }
}