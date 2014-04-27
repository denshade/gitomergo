<?php


namespace merger;

use \PhpParser\Node;

class AddedNode implements ChangedNode
{
    /**
     * @var Node
     */
    private $node;

    /**
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param Node $node
     */
    public function __construct(Node $node)
    {
        $this->node = $node;
    }

    /**
     * @return boolean
     */
    public function isAddedUse()
    {
        if ($this->node instanceof \PhpParser\Node\Stmt\UseUse) {
            return true;
        }
        return false;
    }
} 