<?php


namespace merger;


class RemovedNode implements ChangedNode
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
        return false;
    }
} 