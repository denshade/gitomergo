<?php

namespace merger\usestatementmerger;


use PhpParser\Node;
use PhpParser\Node\Stmt\UseUse;

class UseStatementTraverser extends \PhpParser\NodeVisitorAbstract
{
    /**
     * @var string[]
     */
    private $fullUseStatements = array();
    /**
     * @param Node $node
     */
    public function enterNode(Node $node)
    {
        //UseUse => Name => parts
        if ($node instanceof UseUse)
        {
            foreach($node->getIterator() as $name) //Name
            {
                if ($name instanceof \Traversable) //Why do we revisit the same bloody element, but once totally not array?
                foreach ($name as $parts)
                {
                    $fullUse = implode('\\', $parts);
                    $this->fullUseStatements []= $fullUse;
                }

            }
        }
    }

    /**
     * @return \string[]
     */
    public function getFullUseStatements()
    {
        return $this->fullUseStatements;
    }



}