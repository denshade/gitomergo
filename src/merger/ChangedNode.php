<?php


namespace merger;

use PhpParser\Node;


interface ChangedNode {
    /**
     * @return boolean
     */
    public function isAddedUse();

    /**
     * @return Node
     */
    public function getNode();
} 