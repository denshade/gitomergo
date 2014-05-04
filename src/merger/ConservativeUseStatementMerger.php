<?php

namespace merger;

use merger\usestatementmerger\UseStatement;
use merger\usestatementmerger\UseStatementTraverser;
use PhpParser\Lexer;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeTraverser;

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
             * @var UseStatement $myUseStatement
             */
            $fullQualifiedNameMap []= $myUseStatement->getFullQualifiedName();
        }
        foreach($theirUseStatements as $theirUseStatement)
        {
            /**
             * @var UseStatement $theirUseStatement
             */
            $fullQualifiedNameMap []= $theirUseStatement->getFullQualifiedName();
        }

        $resultStatements = $this->writeUseStatements($myStatements, $fullQualifiedNameMap);
        return $resultStatements;
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

    private function writeUseStatements($myStatements, $fullQualifiedNameMap)
    {
        sort($fullQualifiedNameMap);
        $uses = [];
        foreach($fullQualifiedNameMap as $fullUse)
        {
            $parts = explode('\\', $fullUse);
            $useUse = new UseUse(
                new Name($parts)
            );
            $uses []= $useUse;
        }
        $useCollection = new Use_($uses);
        //Namespace_
        //subnodes = array(
        //stmts = array(
        //Use_
        //subnodes = array
        //uses = array
        //UseUse
        //TODO this can be wrong. Not all code trees have a name space.s
        if (count($uses) === 0)
        {
            return $myStatements;
        }
        if (! ($myStatements[0]->stmts[0] instanceof Use_))
        {
            array_unshift($myStatements[0]->stmts, $useCollection);
        } else
        {
            $myStatements[0]->stmts[0] = $useCollection;
        }
        return $myStatements;
    }
}