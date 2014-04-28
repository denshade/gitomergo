<?php

namespace merger;

use merger\usestatementmerger\UseStatement;
use merger\usestatementmerger\UseStatementTraverser;
use PhpParser\Lexer;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use merger\ChangedNode;
use ReflectionClass;

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
     * @param array $myStatements
     * @return array
     */
    private function filterOutUseStatements($myStatements)
    {
        $results = array();
        $traverser     = new NodeTraverser;
        $useStatementTrav = new UseStatementTraverser();
        $traverser->addVisitor($useStatementTrav);
        $traverser->traverse($myStatements);
        foreach($useStatementTrav->getFullUseStatements() as $useStatementText)
        {
            $useStatement = new UseStatement();
            $useStatement->setFullQualifiedName($useStatementText);
            $results []= $useStatement;
        }
        return $results;
    }
}